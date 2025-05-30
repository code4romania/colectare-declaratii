<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\DeclarationType;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;

class CollectPage extends SimplePage
{
    use InteractsWithFormActions;

    // protected static string $layout = 'components.layout.public';

    protected static string $view = 'livewire.collect-page';

    public ?array $data = [];

    protected bool $recentlySuccessful = false;

    public function getMaxWidth(): MaxWidth
    {
        return MaxWidth::FourExtraLarge;
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                TextInput::make('full_name')
                    ->label('Nume și prenume')
                    ->maxLength(255),

                TextInput::make('institution')
                    ->label('Instituție')
                    ->maxLength(255),

                TextInput::make('position')
                    ->label('Funcție')
                    ->maxLength(255),

                Select::make('county_id')
                    ->label('Județ'),

                Select::make('locality_id')
                    ->label('Localitate'),

                DatePicker::make('date')
                    ->label('Dată completare'),

                Select::make('type')
                    ->label('Tip declaratie')
                    ->options(DeclarationType::options())
                    ->required(),

                // TODO: disk configuration
                FileUpload::make('file')
                    ->acceptedFileTypes(['application/pdf'])
                    ->preserveFilenames(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('handle')
                ->label(__('app.submit'))
                ->color('primary')
                ->submit('handle'),
        ];
    }

    public function handle(): void
    {
        dd($this->form->getState());
    }
}
