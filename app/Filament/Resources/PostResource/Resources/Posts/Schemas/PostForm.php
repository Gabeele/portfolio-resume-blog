<?php

namespace App\Filament\Resources\PostResource\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('body')
                    ->required(),
                Toggle::make('publish')
                    ->required(),
                TextInput::make('meta_title'),
                TextInput::make('meta_description'),
                TextInput::make('meta_keywords'),
                DateTimePicker::make('published_at'),
                Select::make('user_id')
                    ->relationship('user', 'id'),
            ]);
    }
}
