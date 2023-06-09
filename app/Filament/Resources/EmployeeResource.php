<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Employee Management';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->label('Countries')
                    ->options(Country::all()->pluck('name', 'id')->toArray())
                    ->reactive()->required()->searchable()
                    ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),

                Forms\Components\Select::make('state_id')
                    ->label('States')
                    ->options(function(callable $get){
                        $country = Country::find($get('country_id'));
                        if(!$country){
                            return State::all()->pluck('name','id');
                        }
                        return $country->state->pluck('name','id');
                    })
                    ->reactive()->required()->searchable()
                    ->afterStateUpdated(fn (callable $set) => $set('city_id',null)),

                Forms\Components\Select::make('city_id')
                    ->label('Cities')
                    ->options(function (callable $get){
                        $state = State::find($get('state_id'));
                        if(!$state){
                            return City::all()->pluck('name','id');
                        }
                        return $state->city->pluck('name','id');
                    })
                    ->reactive()->required()->searchable()
                    ->reactive()->required()->searchable(),

                Forms\Components\Select::make('department_id')
                    ->label('Department')
                    ->options(Department::all()->pluck('name', 'id'))
                    ->required()->searchable(),

                Forms\Components\FileUpload::make('attachment')
                ->imagePreviewHeight('250')
                ->loadingIndicatorPosition('left')
                ->panelAspectRatio('2:1')
                ->panelLayout('integrated')
                ->removeUploadedFileButtonPosition('right')
                ->uploadButtonPosition('left')
                ->uploadProgressIndicatorPosition('left'),

                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date')
                    ->required(),
                Forms\Components\DatePicker::make('hired_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')->searchable(),
                Tables\Columns\TextColumn::make('state.name')->searchable(),
                Tables\Columns\TextColumn::make('city.name'),
                Tables\Columns\TextColumn::make('department.name'),
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('zip_code'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date(),
                Tables\Columns\TextColumn::make('hired_date')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }    
}
