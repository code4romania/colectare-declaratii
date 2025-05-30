<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\DeclarationType;
use App\Models\County;
use App\Models\Declaration;
use App\Models\Locality;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

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

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getHeading(): string|Htmlable
    {
        return 'Colectează declarația de avere sau interese';
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                TextInput::make('official_name')
                    ->hint(__('app.hints.full_name'))
                    ->label(__('app.fields.full_name'))
                    ->maxLength(255),

                TextInput::make('institution')
                    ->label(__('app.fields.institution'))
                    ->maxLength(255),

                TextInput::make('position')
                    ->label(__('app.fields.position'))
                    ->autocomplete()
                    ->datalist(['Director', 'Administrator', 'Manager'])
                    ->maxLength(255),

                Select::make('county_id')
                    ->label(__('app.fields.county'))
                    ->options(County::pluck('name', 'id'))
                    ->searchable()
                    ->live(),

                Select::make('locality_id')
                    ->label(__('app.fields.locality'))
                    ->disabled(fn (Get $get) => blank($get('county_id')))
                    ->options(fn (Get $get) => Locality::where('county_id', $get('county_id'))->pluck('name', 'id'))
                    ->searchable(),

                DatePicker::make('date')
                    ->default(now())
                    ->label(__('app.fields.fill_date')),

                Radio::make('type')
                    ->label(__('app.fields.type'))
                    ->options(DeclarationType::options())
                    ->inline()
                    ->required(),

                FileUpload::make('file')
                    ->hint(new HtmlString(__('app.hints.file')))
                    ->acceptedFileTypes(['application/pdf'])
                    ->disk('s3')
                    ->required()
                    ->saveUploadedFileUsing()
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
        $this->form->validate();

        $data = $this->form->getState();

        $data['ip_address'] = request()->ip();

        Declaration::create($data);

        $this->recentlySuccessful = true;
    }

    public function refresh(): void
    {
        $this->recentlySuccessful = false;
        $this->form->fill();
    }

    public function refreshAction(): Action
    {
        return Action::make('refreshAction')
            ->label(__('app.refresh'))
            ->icon('heroicon-o-arrow-path')
            ->extraAttributes(['class' => 'mt-2'])

            ->action(fn () => $this->refresh());
    }

}
