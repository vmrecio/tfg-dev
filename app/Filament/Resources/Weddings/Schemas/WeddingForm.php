<?php

namespace App\Filament\Resources\Weddings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class WeddingForm
{
    public static function configure(Schema $schema): Schema
    {
        return self::create($schema);
    }

    public static function create(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Fieldset::make('Pareja')->columns(2)->columnSpanFull()->schema([
                Select::make('couple_member_1')
                    ->label('Miembro 1')
                    ->placeholder('Buscar por nombre o email')
                    ->options(fn () => self::userOptions())
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(fn (string $search) => self::userOptions($search))
                    ->getOptionLabelUsing(fn ($value) => self::userOptionLabelById($value))
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        $id1 = (int) $get('couple_member_1');
                        $id2 = (int) $get('couple_member_2');
                        if ($id1 && $id2) {
                            $derived = self::computePairDerived($id1, $id2);
                            if ($derived) {
                                [$name, $slug, $sig] = $derived;
                                $set('name', $name);
                                $set('slug', $slug);
                                $set('pair_signature', $sig);
                            }
                        }
                    }),

                Select::make('couple_member_2')
                    ->label('Miembro 2')
                    ->placeholder('Buscar por nombre o email')
                    ->options(fn () => self::userOptions())
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(fn (string $search) => self::userOptions($search))
                    ->getOptionLabelUsing(fn ($value) => self::userOptionLabelById($value))
                    ->live()
                    ->different('couple_member_1')
                    ->validationAttribute('Miembro 2')
                    ->validationMessages([
                        'different' => 'Miembro 1 y Miembro 2 deben ser diferentes.',
                    ])
                    ->afterStateUpdated(function ($get, $set) {
                        $id1 = (int) $get('couple_member_1');
                        $id2 = (int) $get('couple_member_2');
                        if ($id1 && $id2) {
                            $derived = self::computePairDerived($id1, $id2);
                            if ($derived) {
                                [$name, $slug, $sig] = $derived;
                                $set('name', $name);
                                $set('slug', $slug);
                                $set('pair_signature', $sig);
                            }
                        }
                    }),

                ]),

                // Campo hidden para enviar la firma única de ids de pareja
                Hidden::make('pair_signature')
                    ->dehydrated(),

                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->readOnly(),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(table: 'weddings', column: 'slug', ignoreRecord: true)
                    ->readOnly(),

                DatePicker::make('event_date')
                    ->label('Fecha del evento')
                    ->native(false)
                    ->closeOnDateSelection(),

                TextInput::make('location')
                    ->label('Ubicación')
                    ->placeholder('Ciudad o lugar del evento')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Descripción')
                    ->placeholder('Notas, detalles logísticos, etc.')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function edit(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DatePicker::make('event_date')
                    ->label('Fecha del evento')
                    ->native(false)
                    ->closeOnDateSelection(),

                TextInput::make('location')
                    ->label('Ubicación')
                    ->placeholder('Ciudad o lugar del evento')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Descripción')
                    ->placeholder('Notas, detalles logísticos, etc.')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Compone la etiqueta de visualización para un usuario con Nombre Apellido (Email)
     */
    private static function userDisplayLabel(?\App\Models\User $u): ?string
    {
        if (! $u) {
            return null;
        }
        $full = trim($u->name . ' ' . ($u->last_name ?? ''));
        return $full . ' (' . $u->email . ')';
    }

    /**
     * Devuelve 50 opciones de usuarios ordenados alfabéticamente por nombre y formateados bajo el método userDisplayLabel.
     */
    private static function userOptions(?string $search = null): array
    {
        $query = \App\Models\User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'user'));

        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderBy('name')
            ->orderBy('last_name')
            ->limit(50)
            ->get()
            ->mapWithKeys(fn ($u) => [$u->id => self::userDisplayLabel($u)])
            ->toArray();
    }

    /**
     * Devuelve el usuario correspondiente a un id formateado bajo el método userDisplayLabel.
     */
    private static function userOptionLabelById($id): ?string
    {
        if (! $id) {
            return null;
        }
        $u = \App\Models\User::query()->select(['id','name','last_name','email'])->find($id);
        return self::userDisplayLabel($u);
    }

    /**
     * Dado un par de IDs de usuarios, devuelve un array con:
     * - Nombre para la boda
     * - Slug para la boda
     * - Firma única de ids de pareja
     */
    private static function computePairDerived(int $id1, int $id2): ?array
    {
        $u1 = \App\Models\User::find($id1);
        $u2 = \App\Models\User::find($id2);
        if (! $u1 || ! $u2) {
            return null;
        }

        $first1 = trim($u1->name);
        $first2 = trim($u2->name);
        $name = "Boda de {$first1} y {$first2}";

        $full1 = trim($u1->name . ' ' . ($u1->last_name ?? ''));
        $full2 = trim($u2->name . ' ' . ($u2->last_name ?? ''));
        $baseSlug = \Illuminate\Support\Str::slug("boda de {$full1} y {$full2}");
        $slug = $baseSlug;
        $i = 1;
        while (\App\Models\Wedding::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $sig = $id1 < $id2 ? ($id1.'-'.$id2) : ($id2.'-'.$id1);

        return [$name, $slug, $sig];
    }
}
