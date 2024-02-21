<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\SiteSetting;
use App\Models\SocialMedia;
use Filament\Actions\Action;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
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
        $siteSetting = SiteSetting::first() ?? NULL;

        $socialMedia = $siteSetting->social_media;
        $socialMedia = $socialMedia->map(function ($item) {
            return [
                'site_setting_social_media_id' => $item->pivot->id,
                'site_setting_id' => $item->pivot->site_setting_id,
                'social_media_id' => $item->pivot->social_media_id,
                'url' => $item->pivot->url,
            ];
        })->toArray() ?? NULL;

        $data = [
            'company_name' => $siteSetting ? $siteSetting->company_name : null,
            'site_title' => $siteSetting ? $siteSetting->site_title : null,
            'site_description' => $siteSetting ? $siteSetting->site_description : null,
            'site_logo' => $siteSetting ? $siteSetting->site_logo : null,
            'site_google_analytics_tracking_id' => $siteSetting ? $siteSetting->site_google_analytics_tracking_id : null,
            'social_media' => $socialMedia,
        ];

        if ($data) {
            $this->form->fill($data);
        }
    }

    public function Form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        tabs\Tab::make('General Info')
                            ->icon('heroicon-m-information-circle')
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
                            ]),
                        tabs\Tab::make('Site Social Media')
                            ->icon('heroicon-m-at-symbol')
                            ->schema([
                                Repeater::make('social_media')
                                    ->schema([
                                        Select::make('social_media_id')
                                            ->label('Social Media')
                                            ->required()
                                            ->getOptionLabelUsing(fn ($value): ?string => SocialMedia::find($value)?->name)
                                            ->options(function () {
                                                $existingSocialMediaNames = SiteSetting::first()->social_media->pluck('id')->toArray();
                                                $availableSocialMedia = SocialMedia::whereNotIn('id', $existingSocialMediaNames)->get();
                                                return $availableSocialMedia->pluck('name', 'id')->toArray();
                                            })
                                            ->searchable(),
                                        TextInput::make('url')
                                            ->prefix('https://')
                                            ->suffixIcon('heroicon-m-globe-alt')
                                            ->required(),
                                    ])
                                    ->reorderableWithButtons()
                                    ->columns(2)
                            ])
                    ]),
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
            $siteSetting = SiteSetting::firstOrNew();

            $siteSetting->fill([
                'company_name' => $data['company_name'],
                'site_title' => $data['site_title'],
                'site_description' => $data['site_description'],
                'site_logo' => $data['site_logo'],
                'site_google_analytics_tracking_id' => $data['site_google_analytics_tracking_id'],
            ])->save();

            $socialMediaData = $data['social_media'] ?? [];

            // Create an array to store only the IDs of the social media
            $socialMediaIds = [];

            foreach ($socialMediaData as $socialMediaItem) {
                // Add the social media ID to the array
                $socialMediaIds[] = $socialMediaItem['social_media_id'];
            }

            // Sync the site's social media with the provided IDs
            $siteSetting->social_media()->sync($socialMediaIds);

            // Update the URLs for existing social media
            foreach ($socialMediaData as $socialMediaItem) {
                $siteSetting->social_media()->updateExistingPivot($socialMediaItem['social_media_id'], [
                    'url' => $socialMediaItem['url'],
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
