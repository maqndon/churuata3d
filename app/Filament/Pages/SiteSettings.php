<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $view = 'filament.pages.site-settings';

    protected static ?string $navigationGroup = 'Settings';

    public function mount(): void
    {
        $data = SiteSetting::select(['company_name', 'site_title', 'site_description', 'site_logo', 'site_google_analytics_tracking_id'])->first() ?? NULL;

        if ($data) {
            $data = $data->attributesToArray();
            $this->form->fill($data);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('company_name')
                    ->required(),
                TextInput::make('site_title')
                    ->required(),
                TextInput::make('site_description')
                    ->required(),
                FileUpload::make('site_logo')
                    ->preserveFilenames()
                    ->directory('site_images')
                    ->image()
                    ->imageEditor()
                    ->maxSize(1024),
                TextInput::make('site_google_analytics_tracking_id'),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {

            $data = $this->form->getState();
            $siteSetting = SiteSetting::first();

            if ($siteSetting) {
                $siteSetting->company_name = $data['company_name'];
                $siteSetting->site_title = $data['site_title'];
                $siteSetting->site_description = $data['site_description'];
                $siteSetting->site_logo = $data['site_logo'];
                $siteSetting->site_google_analytics_tracking_id = $data['site_google_analytics_tracking_id'];
                $siteSetting->update($data);
            } else {
                SiteSetting::create([
                    'company_name' => $data['company_name'],
                    'site_title' => $data['site_title'],
                    'site_description' => $data['site_description'],
                    'site_logo' => $data['site_logo'],
                    'site_google_analytics_tracking_id' => $data['site_google_analytics_tracking_id']
                ]);
            }
        } catch (Halt $exception) {
            return;
        }
        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
