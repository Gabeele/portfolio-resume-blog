<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Certificate","View:Certificate","Create:Certificate","Update:Certificate","Delete:Certificate","Restore:Certificate","ForceDelete:Certificate","ForceDeleteAny:Certificate","RestoreAny:Certificate","Replicate:Certificate","Reorder:Certificate","ViewAny:Education","View:Education","Create:Education","Update:Education","Delete:Education","Restore:Education","ForceDelete:Education","ForceDeleteAny:Education","RestoreAny:Education","Replicate:Education","Reorder:Education","ViewAny:Project","View:Project","Create:Project","Update:Project","Delete:Project","Restore:Project","ForceDelete:Project","ForceDeleteAny:Project","RestoreAny:Project","Replicate:Project","Reorder:Project","ViewAny:Reference","View:Reference","Create:Reference","Update:Reference","Delete:Reference","Restore:Reference","ForceDelete:Reference","ForceDeleteAny:Reference","RestoreAny:Reference","Replicate:Reference","Reorder:Reference","ViewAny:Resume","View:Resume","Create:Resume","Update:Resume","Delete:Resume","Restore:Resume","ForceDelete:Resume","ForceDeleteAny:Resume","RestoreAny:Resume","Replicate:Resume","Reorder:Resume","ViewAny:Skill","View:Skill","Create:Skill","Update:Skill","Delete:Skill","Restore:Skill","ForceDelete:Skill","ForceDeleteAny:Skill","RestoreAny:Skill","Replicate:Skill","Reorder:Skill","ViewAny:Summary","View:Summary","Create:Summary","Update:Summary","Delete:Summary","Restore:Summary","ForceDelete:Summary","ForceDeleteAny:Summary","RestoreAny:Summary","Replicate:Summary","Reorder:Summary","ViewAny:User","View:User","Create:User","Update:User","Delete:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","ViewAny:WorkExperience","View:WorkExperience","Create:WorkExperience","Update:WorkExperience","Delete:WorkExperience","Restore:WorkExperience","ForceDelete:WorkExperience","ForceDeleteAny:WorkExperience","RestoreAny:WorkExperience","Replicate:WorkExperience","Reorder:WorkExperience","ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","View:Backups","ViewAny:Post","View:Post","Create:Post","Update:Post","Delete:Post","Restore:Post","ForceDelete:Post","ForceDeleteAny:Post","RestoreAny:Post","Replicate:Post","Reorder:Post","ViewAny:Tag","View:Tag","Create:Tag","Update:Tag","Delete:Tag","Restore:Tag","ForceDelete:Tag","ForceDeleteAny:Tag","RestoreAny:Tag","Replicate:Tag","Reorder:Tag"]},{"name":"Standard","guard_name":"web","permissions":["ViewAny:Certificate","View:Certificate","Create:Certificate","Update:Certificate","Delete:Certificate","ViewAny:Education","View:Education","Create:Education","Update:Education","Delete:Education","ViewAny:Project","View:Project","Create:Project","Update:Project","Delete:Project","ViewAny:Reference","View:Reference","Create:Reference","Update:Reference","Delete:Reference","ViewAny:Resume","View:Resume","Create:Resume","Update:Resume","Delete:Resume","ViewAny:Skill","Create:Skill","Update:Skill","Delete:Skill","ViewAny:Summary","View:Summary","Create:Summary","Update:Summary","Delete:Summary","ViewAny:WorkExperience","View:WorkExperience","Create:WorkExperience","Update:WorkExperience","Delete:WorkExperience","ViewAny:Post","View:Post","Create:Post","Update:Post","Delete:Post","ViewAny:Tag","View:Tag","Create:Tag","Update:Tag","Delete:Tag"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (!blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (!blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (!blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
