<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\DeclarationType;
use App\Models\County;
use App\Models\Declaration;
use App\Models\Locality;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class CollectPage extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

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
        return 'Colectează declarații de avere și interese';
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->columns()
            ->schema([
                TextInput::make('full_name')
                    ->hint(__('app.hints.full_name'))
                    ->label(__('app.fields.full_name'))
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('institution')
                    ->label(__('app.fields.institution'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('position')
                    ->label(__('app.fields.position'))
                    ->autocomplete()
                    ->datalist(['Director', 'Administrator', 'Manager'])
                    ->maxLength(255)
                    ->required(),

                Select::make('county_id')
                    ->label(__('app.fields.county'))
                    ->options(County::pluck('name', 'id'))
                    ->searchable()
                    ->lazy()
                    ->required(),

                Select::make('locality_id')
                    ->label(__('app.fields.locality'))
                    ->disabled(fn (Get $get) => blank($get('county_id')))
                    ->options(fn (Get $get) => Locality::where('county_id', $get('county_id'))->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Radio::make('type')
                    ->label(__('app.fields.type'))
                    ->options(DeclarationType::options())
                    ->inline()
                    ->inlineLabel(false)
                    ->required(),

                DatePicker::make('date')
                    ->label(__('app.fields.fill_date'))
                    ->required(),

                FileUpload::make('filename')
                    ->label(__('app.fields.file'))
                    ->hint(new HtmlString(__('app.hints.file')))
                    ->acceptedFileTypes(['application/pdf'])
                    ->storeFileNamesIn('original_filename')
                    ->disk(config('filesystems.default'))
                    ->required()
                    ->columnSpanFull(),
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
        try {
            $this->rateLimit(1);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.title'))
                ->body(__('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->danger()
                ->send();

            return;
        }

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
