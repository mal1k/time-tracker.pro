<?php

namespace Database\Seeders;

use App\Models\Getway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentGetwaySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $getways = [
      [
        "id" => 1,
        "name" => "paypal",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/paypal.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Paypal",
        "currency" => "USD",
        "status" => 1,
        "phone_status" => 0,
        "data" => "{\"client_id\":\"ARKsbdD1qRpl3WEV6XCLuTUsvE1_5NnQuazG2Rvw1NkMG3owPjCeAaia0SXSvoKPYNTrh55jZieVW7xv\",\"client_secret\":\"EJed2cGACzB2SJFQwSannKAA1gyBjKkwlKh1o8G75zQHYzAgLQ3n7f9EfeNCZgtfPDMxyFzfp6oQWPia\"}",
        "created_at" => "2021-04-20 08:59:17",
        "updated_at" => "2021-04-20 09:27:12"
      ],
      [
        "id" => 2,
        "name" => "stripe",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/stripe.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Stripe",
        "currency" => "USD",
        "status" => 1,
        "phone_status" => 0,
        "data" => "{\"publishable_key\":\"pk_test_51I8GqvBRq7fsgmoHB37mXDC3oNVtsJBMQRYeRLUykmuWlqihZ1kDvYeLUeno9Nkqze4axZF0nLeeqkdYJP42S06u00GEiuG8CS\",\"secret_key\":\"sk_test_51I8GqvBRq7fsgmoHldttMcxnaiSwu5thxGVELXwxd9la5NNttvNBICXTY7r0TkTEDKqzdIl9KZIJu6sNMJqMM1MZ00I8obAU6P\"}",
        "created_at" => "2021-04-20 08:59:17",
        "updated_at" => "2021-04-20 08:59:17"
      ],
      [
        "id" => 3,
        "name" => "razorpay",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/razorpay.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Razorpay",
        "currency" => "INR",
        "status" => 1,
        "phone_status" => 0,
        "data" => "{\"key_id\":\"rzp_test_siWkeZjPLsYGSi\",\"key_secret\":\"jmIzYyrRVMLkC9BwqCJ0wbmt\"}",
        "created_at" => "2021-04-20 08:59:17",
        "updated_at" => "2021-04-20 08:59:17"
      ],
      [
        "id" => 4,
        "name" => "instamojo",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/instamojo.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Instamojo",
        "currency" => "INR",
        "status" => 1,
        "phone_status" => 1,
        "data" => "{\"x_api_key\":\"test_0027bc9da0a955f6d33a33d4a5d\",\"x_auth_token\":\"test_211beaba149075c9268a47f26c6\"}",
        "created_at" => "2021-04-20 08:59:17",
        "updated_at" => "2021-04-20 09:04:06"
      ],
      [
        "id" => 5,
        "name" => "toyyibpay",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/toyyibpay.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Toyyibpay",
        "currency" => "USD",
        "status" => 1,
        "phone_status" => 1,
        "data" => "{\"user_secret_key\":\"v4nm8x50-bfb4-7f8y-evrs-85flcysx5b9p\",\"cateogry_code\":\"5cc45t69\"}",
        "created_at" => "2021-04-20 08:59:18",
        "updated_at" => "2021-04-20 09:26:11"
      ],
      [
        "id" => 6,
        "name" => "mollie",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/mollie.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Mollie",
        "currency" => "USD",
        "status" => 1,
        "phone_status" => 0,
        "data" => "{\"api_key\":\"test_WqUGsP9qywy3eRVvWMRayxmVB5dx2r\"}",
        "created_at" => "2021-04-20 08:59:18",
        "updated_at" => "2021-04-20 09:26:47"
      ],
      [
        "id" => 7,
        "name" => "paystack",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/paystack.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Paystack",
        "currency" => "NGN",
        "status" => 1,
        "phone_status" => 0,
        "data" => "{\"public_key\":\"pk_test_84d91b79433a648f2cd0cb69287527f1cb81b53d\",\"secret_key\":\"sk_test_cf3a234b923f32194fb5163c9d0ab706b864cc3e\"}",
        "created_at" => "2021-04-20 08:59:18",
        "updated_at" => "2021-04-20 09:34:04"
      ],
      [
        "id" => 8,
        "name" => "free",
        "logo" => "",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "",
        "currency" => "USD",
        "status" => 1,
        "phone_status" => 0,
        "data" => null,
        "created_at" => "2021-04-20 08:59:18",
        "updated_at" => "2021-04-20 08:59:18"
      ],
      [
        "id" => 9,
        "name" => "flutterwave",
        "logo" => "uploads\\\\\/payment_gateway\\\\\/flutterwave.png",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\Flutterwave",
        "currency" => "NGN",
        "status" => 1,
        "phone_status" => 1,
        "data" => "{\"public_key\":\"FLWPUBK_TEST-f448f625c416f69a7c08fc6028ebebbf-X\",\"secret_key\":\"FLWSECK_TEST-561fa94f45fc758339b1e54b393f3178-X\",\"encryption_key\":\"FLWSECK_TEST498417c2cc01\",\"payment_options\":\"card\"}",
        "created_at" => "2021-04-20 08:59:18",
        "updated_at" => "2021-05-22 06:08:28"
      ],
      [
        "id" => 10,
        "name" => "Bank Transfer",
        "logo" => "",
        "rate" => 10.0,
        "charge" => 2.0,
        "namespace" => "App\\Lib\\CustomGetway",
        "currency" => "USD",
        "status" => 1,
        "phone_status" => 0,
        "data" => "{\"bank_name\":\"IBBL\",\"branch_name\":\"GEC\",\"account_holder_name\":\"John Doe\",\"account_number\":\"0123654789\"}",
        "created_at" => "2021-04-20 08:59:18",
        "updated_at" => "2021-05-22 06:08:08"
      ]
    ];


    Getway::insert($getways);
  }
}
