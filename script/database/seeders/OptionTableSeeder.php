<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Option;
use App\Models\Menu;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Database\Seeder;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::create([
            'key'=> 'auto_enroll_after_payment',
            'value'=> 'on',
        ]);

        Option::create([
            'key'=> 'currency',
            'value'=> 'USD',
        ]);

        Option::create([
            'key'=> 'tax',
            'value'=> 2.5,
        ]);

        Option::create([
            'key'=> 'invoice_prefix',
            'value'=> 'INVOICE_',
        ]);

        Option::create([
            'key'=> 'support_email',
            'value'=> 'admin@admin.com',
        ]);

        $value = [
            'status'  => "on", //or off
            'days'     => 10,
            'assign_default_plan' => "on",
            'alert_message' => "Hi, your plan will expire soon!",
            'expire_message' => "Your plan is expired!",
        ];
            
        $cron        = new Option();
        $cron->key   = 'cron_option';
        $cron->value = json_encode($value);
        $cron->save();

        Option::create([
            'key'=>'seo',
            'value'=>'{"title":"Timelock","description":"test","canonical":null,"tags":"test,test1","twitterTitle":"@timelock"}',
        ]);

        Option::create([
            'key'=> 'currency_icon',
            'value'=> '$',
        ]);


        

        $theme        =  new Option();
        $theme->key   = 'theme_settings';
        $theme->value = '{"footer_description":"Lorem ipsum dolor sit amet, consect etur adi pisicing elit sed do eiusmod tempor incididunt ut labore.","newsletter_address":"88 Broklyn Golden Street, New York. USA needhelp@ziston.com","social":[{"icon":"ri:facebook-fill","link":"#"},{"icon":"ri:twitter-fill","link":"#"},{"icon":"ri:google-fill","link":"#"},{"icon":"ri:instagram-fill","link":"#"},{"icon":"ri:pinterest-fill","link":"#"}],"top_left_text":"Free 14-Days Trial","top_left_link":"\/register","top_right_text":"Login","top_right_link":"\/login"}';
        $theme->save();

        Option::create([
            'key'=> 'header',
            'value'=> '{"title":"Spend less time tracking and more time growing.","short_title":"The all-in-one work time tracker for managing field or remote teams.","youtube_link":"https:\/\/www.youtube.com\/watch?v=2xc9YKdlLW0","get_start_form":"show"}',
        ]);
  

        $terms = array(
          array('id' => '1','title' => 'New templates are here—find your favorite!','slug' => 'new-templates-are-here-find-your-favorite','type' => 'announcement','status' => '1','featured' => '1','created_at' => '2021-03-28 17:50:40','updated_at' => '2021-03-28 17:50:40'),
          array('id' => '2','title' => 'Team’s Report about the trip to the road show','slug' => 'teams-report-about-the-trip-to-the-road-show','type' => 'blog','status' => '1','featured' => '1','created_at' => '2021-03-28 18:16:09','updated_at' => '2021-03-28 18:25:44'),
          array('id' => '3','title' => 'Trip to the road show Report about the team’s','slug' => 'trip-to-the-road-show-report-about-the-teams','type' => 'blog','status' => '1','featured' => '1','created_at' => '2021-03-28 18:17:13','updated_at' => '2021-03-28 18:17:13'),
          array('id' => '4','title' => 'Trip to the road show Report about the team’sTrip to the road show Report about the team’s','slug' => 'trip-to-the-road-show-report-about-the-teamstrip-to-the-road-show-report-about-the-teams','type' => 'blog','status' => '1','featured' => '1','created_at' => '2021-03-28 18:17:53','updated_at' => '2021-03-28 18:17:53'),
          array('id' => '5','title' => 'Screenshot Capture','slug' => 'screenshot-capture5','type' => 'feature','status' => '1','featured' => '1','created_at' => '2021-03-31 04:24:32','updated_at' => '2021-05-27 11:37:26'),
          array('id' => '6','title' => 'Move team ideas to action faster.','slug' => 'move-team-ideas-to-action-faster6','type' => 'aboutsection','status' => '1','featured' => '1','created_at' => '2021-03-31 04:56:04','updated_at' => '2021-05-27 11:35:56'),
          array('id' => '7','title' => 'Project Analytics','slug' => 'project-analytics7','type' => 'analytic','status' => '1','featured' => '1','created_at' => '2021-03-31 05:18:54','updated_at' => '2021-05-27 11:34:03'),
          array('id' => '8','title' => 'Mail Activity','slug' => 'mail-activity8','type' => 'feature','status' => '1','featured' => '1','created_at' => '2021-03-31 06:34:07','updated_at' => '2021-05-23 10:29:00'),
          array('id' => '9','title' => 'Location Tracking','slug' => 'location-tracking9','type' => 'feature','status' => '1','featured' => '1','created_at' => '2021-05-23 10:27:56','updated_at' => '2021-05-27 11:38:13'),
          array('id' => '10','title' => 'Organize Your Team','slug' => 'organize-your-team10','type' => 'feature','status' => '1','featured' => '1','created_at' => '2021-05-23 11:03:56','updated_at' => '2021-05-23 11:08:56'),
          array('id' => '11','title' => 'Voluptates ipsum ab','slug' => 'voluptates-ipsum-ab','type' => 'page','status' => '0','featured' => '1','created_at' => '2021-05-27 11:39:37','updated_at' => '2021-05-27 11:39:37')
        );



        Term::insert($terms);
        
        $termmetas = array(
  array('id' => '1','term_id' => '1','key' => 'announcement_desc','value' => 'test'),
  array('id' => '2','term_id' => '2','key' => 'excerpt','value' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et doloreLorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore'),
  array('id' => '3','term_id' => '2','key' => 'thum_image','value' => 'uploads/1/21/03/1695501968131722.jpg'),
  array('id' => '4','term_id' => '2','key' => 'description','value' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

vLorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore'),
  array('id' => '5','term_id' => '3','key' => 'excerpt','value' => 'Trip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’s'),
  array('id' => '6','term_id' => '3','key' => 'thum_image','value' => 'uploads/1/21/03/1695500660441420.jpg'),
  array('id' => '7','term_id' => '3','key' => 'description','value' => 'Trip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’s'),
  array('id' => '8','term_id' => '4','key' => 'excerpt','value' => 'vTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’s'),
  array('id' => '9','term_id' => '4','key' => 'thum_image','value' => 'uploads/1/21/03/1695500702735764.jpg'),
  array('id' => '10','term_id' => '4','key' => 'description','value' => 'Trip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’sTrip to the road show Report about the team’s'),
  array('id' => '11','term_id' => '5','key' => 'feature_meta','value' => '{"short_description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","page_content":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.\\r\\n\\r\\nLorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","color":"#3fa9f5","icon":"uploads\\/features\\/21\\/05\\/1700547030490323.png"}'),
  array('id' => '12','term_id' => '6','key' => 'about_meta','value' => '{"short_description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","short_title":"COLLABORATE","page_content":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.\\r\\n\\r\\nLorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","button_status":"1","button_text":"Learn More","image":"uploads\\/21\\/03\\/1695736124405242.png"}'),
  array('id' => '13','term_id' => '7','key' => 'analytic_meta','value' => '{"short_description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","short_title":"Short Title","page_content":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.\\r\\n\\r\\nLorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.\\r\\n\\r\\nLorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","button_status":"1","button_text":"Learn More","image":"uploads\\/21\\/03\\/1695736170018130.svg"}'),
  array('id' => '14','term_id' => '8','key' => 'feature_meta','value' => '{"short_description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","page_content":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.\\r\\n\\r\\nLorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","color":"#464ca0","icon":"uploads\\/21\\/03\\/1695728216691705.svg"}'),
  array('id' => '15','term_id' => '9','key' => 'feature_meta','value' => '{"short_description":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.","page_content":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.\\r\\n\\r\\nLorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","color":"#0236b1","icon":"uploads\\/features\\/21\\/05\\/1700544566136981.svg"}'),
  array('id' => '16','term_id' => '10','key' => 'feature_meta','value' => '{"short_description":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","page_content":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.\\r\\n\\r\\nLorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam quaerat tempore, nemo, nesciunt ipsa nihil commodi amet reprehenderit aliquid accusamus assumenda iure facere sequi? In quas minima pariatur modi expedita, nesciunt blanditiis recusandae aspernatur temporibus, quibusdam voluptatem commodi iste perspiciatis molestiae voluptatibus ratione animi quo ipsam ab rem. Ea, reprehenderit.","color":"#f8dd30","icon":"uploads\\/features\\/21\\/05\\/1700546830887668.svg"}'),
  array('id' => '17','term_id' => '11','key' => 'page','value' => '{"page_excerpt":"Quisquam aut alias c","page_content":"Sit quis unde deseru"}')
);

          

          Termmeta::insert($termmetas);


          $languages = array(
            array('id' => '1','name' => 'en','position' => NULL,'data' => 'English','status' => '1','created_at' => '2021-03-31 09:12:47','updated_at' => '2021-03-31 09:12:47')
          );

          Language::insert($languages);

          $menu = array(
              array('id' => '2','name' => 'Header','position' => 'header','data' => '[{"text":"Home","href":"/","icon":"","target":"_self","title":""},{"text":"Pricing","href":"/pricing","icon":"empty","target":"_self","title":""},{"text":"About","href":"/about","icon":"empty","target":"_self","title":""},{"text":"Blog","icon":"","href":"/blog","target":"_self","title":""},{"text":"Contact","href":"/contact","icon":"empty","target":"_self","title":""}]','lang' => 'en','status' => '1','created_at' => '2021-04-08 15:54:56','updated_at' => '2021-04-08 17:13:43'),
              array('id' => '3','name' => 'Explore','position' => 'footer_left','data' => '[{"text":"Pricing","icon":"","href":"/pricing","target":"_self","title":""},{"text":"About","icon":"empty","href":"/about","target":"_self","title":""},{"text":"Features","icon":"empty","href":"/feature","target":"_self","title":""},{"text":"Blog","icon":"empty","href":"/blog","target":"_self","title":""},{"text":"Contact","icon":"empty","href":"/contact","target":"_self","title":""}]','lang' => 'en','status' => '1','created_at' => '2021-04-08 17:51:45','updated_at' => '2021-04-10 08:36:31'),
              array('id' => '4','name' => 'Quick Links','position' => 'footer_right','data' => '[{"text":"Dashboard","icon":"empty","href":"/user/dashboard","target":"_self","title":""},{"text":"Login","icon":"","href":"/login","target":"_self","title":""},{"text":"Register","icon":"empty","href":"/register","target":"_self","title":""},{"text":"Support","icon":"empty","href":"/user/support","target":"_self","title":""},{"text":"Privacy Policy","icon":"empty","href":"#","target":"_self","title":""}]','lang' => 'en','status' => '1','created_at' => '2021-04-10 08:38:04','updated_at' => '2021-04-10 08:46:21')
          );

          Menu::insert($menu);

    }
}
