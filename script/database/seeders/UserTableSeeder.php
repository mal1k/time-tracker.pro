<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $super = User::create([
    		'role_id' => 1,
    		'name' => 'Admin',
    		'email' => 'admin@admin.com',
    		'password' => Hash::make('rootadmin'),
		]		
	  );
		
    	$roleSuperAdmin = Role::create(['name' => 'superadmin']);
        //create permission
    	$permissions = [
    		[
    			'group_name' => 'dashboard',
    			'permissions' => [
    				'dashboard.index',
    			]
    		],
    		
    		[
    			'group_name' => 'admin',
    			'permissions' => [
    				'admin.create',
    				'admin.edit',
    				'admin.update',
    				'admin.delete',
    				'admin.list',
    			]
    		],
    		[
    			'group_name' => 'role',
    			'permissions' => [
    				'role.create',
    				'role.edit',
    				'role.update',
    				'role.delete',
    				'role.list',

    			]
    		],
            [
                'group_name' => 'page',
                'permissions' => [
                    'page.create',
                    'page.edit',
                    'page.delete',
                    'page.index',

                ]
			],
			[
                'group_name' => '',
                'permissions' => [
                    'website_settings.index',
                ]
			],
			[
                'group_name' => 'transaction',
                'permissions' => [
                    'transaction',
                ]
			],
			[
                'group_name' => 'support',
                'permissions' => [
                    'support.index',
                    'support.delete',
                    'support.create',
                ]
			],
			[
                'group_name' => 'title',
                'permissions' => [
                    'title',
                ]
			],
			
            [
				'group_name' => 'Blog',
				'permissions' => [
					'blog.create',
					'blog.edit',
					'blog.delete',
					'blog.index',
				]
			],
			[
				'group_name' => 'getway',
				'permissions' => [
					'getway.edit',
					'getway.index',
				]
			],

			[
				'group_name' => 'plan',
				'permissions' => [
					'plan.create',
					'plan.edit',
					'plan.index',
					'plan.delete',
				]
			],

			[
				'group_name' => 'report',
				'permissions' => [
					'report',
					
				]
			],

			[
				'group_name' => 'support',
				'permissions' => [
					'support',
				]
			],
			
			[
				'group_name' => 'Settings',
				'permissions' => [
					'system.settings',
					'seo.settings',
					'menu',
				]
			],

			
			[
				'group_name' => 'users',
				'permissions' => [
					'user.create',
					'user.index',
					'user.delete',
					'user.edit',
					'user.verified',
					'user.show',
					'user.banned',
					'user.unverified',
					'user.mail',
					'user.invoice',
				]
			],
			
			[
				'group_name' => 'language',
				'permissions' => [
					'language.index',
					'language.edit',
					'language.create',
					'language.delete',
				]
			],
			
			[
				'group_name' => 'option',
				'permissions' => [
					'option',
				]
			],

			[
				'group_name' => 'review',
				'permissions' => [
					'review.create',
					'review.edit',
					'review.delete',
					'review.index',
				]
			],

			[
				'group_name' => 'order',
				'permissions' => [
					'order.create',
					'order.edit',
					'order.delete',
					'order.index',
					'order.active',
					'order.deactive',
					'order.alert',
				]
			],

			[
				'group_name' => 'web',
				'permissions' => [
					'web.analytic',
					'web.about',
					'web.header',
					'web.feature',
				]
			],


    	];

        //assign permission

    	foreach ($permissions as $key => $row) {
    		foreach ($row['permissions'] as $per) {
    			$permission = Permission::create(['name' => $per, 'group_name' => $row['group_name']]);
    			$roleSuperAdmin->givePermissionTo($permission);
    			$permission->assignRole($roleSuperAdmin);
    			$super->assignRole($roleSuperAdmin);
    		}
    	}

    	
    }
}
