<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema; // Menggunakan Schema sesuai error
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section; // Menyesuaikan namespace v3 Schema Anda
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\Action;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    protected static ?int $navigationSort = 3;

    // Mengubah parameter menjadi Schema sesuai permintaan error trace
    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([ // Menggunakan ->components(), bukan ->schema()
                Section::make('Filter Periode')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Tanggal Mulai')
                            ->default(null),

                        DatePicker::make('endDate')
                            ->label('Tanggal Selesai'),

                        Actions::make([
                            Action::make('reset_filters')
                                ->label('Reset Filter')
                                ->color('danger')
                                ->icon('heroicon-m-arrow-path')
                                ->action(function (Set $set) {
                                    $set('startDate', null);
                                    $set('endDate', null);
                                }),
                        ])->alignCenter(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}