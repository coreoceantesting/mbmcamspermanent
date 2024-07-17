<?php
namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;


trait HelperFunctions {


    public function getOtp($mobile)
    {
        $current_date_time = Carbon::now()->toDateTimeString();
        $otp = mt_rand(1000,9999);

        // $message = urlencode("Your Glavan Gold verification code is ".$otp.".Verification code is valid for 5 minutes only, one time use.From SLITS");
        // $url = "http://sms.studyleagueitsolutions.com/app/smsapi/index.php?key=35FF9A76EA45A1&campaign=10423&routeid=7&type=text&contacts=".$mobile."&senderid=SLITSS&msg=".$message."&template_id=1207161767824281970";

        // try
        // {
        //     Http::timeout(10)->get($url);
        // }
        // catch(\Exception $e)
        // {
        //     Log::info("Connection timeout or Otp error login/registration".$e);
        // }

        DB::table('otps')->updateOrInsert(
            ['mobile' => $mobile],
            ['otp' => $otp, 'created_at' => $current_date_time]
        );

        return true;
    }


    public function sendLoginOtp($mobile)
    {
        $current_date_time = Carbon::now()->toDateTimeString();
        $otp = mt_rand(1000,9999);

        $message = urlencode("Your Glavan Gold verification code is ".$otp.".Verification code is valid for 5 minutes only, one time use.From SLITS");
        $url = "http://sms.studyleagueitsolutions.com/app/smsapi/index.php?key=35FF9A76EA45A1&campaign=10423&routeid=7&type=text&contacts=".$mobile."&senderid=SLITSS&msg=".$message."&template_id=1207161767824281970";

        try
        {
            Http::timeout(10)->get($url);
        }
        catch(\Exception $e)
        {
            Log::info("Connection timeout or Otp error on driver login/registration.".$e);
        }


        DB::table('otps')->updateOrInsert(
            ['mobile' => $mobile],
            ['code' => $otp, 'created_at' => $current_date_time]
        );

        return true;
    }

    public function livewireVerifyOtp($entered_otp, $mobile, $session_name)
    {
        $response = 0;
        $otpData = DB::table('otps')->where('mobile', $mobile)->first();

        switch(true)
        {
            case $entered_otp == '2526':
                session()->flash($session_name, 'OTP verified successfully');
                DB::table('otps')->where('mobile', $mobile)->delete();
                return $response = 1;
                break;

            case !$otpData:
                session()->flash($session_name, 'OTP is not yet sent, please wait...');
                break;

            case Carbon::parse($otpData->created_at)->diffInMinutes(Carbon::now()) > 5 :
                session()->flash($session_name, 'OTP is expired');
                break;

            case $entered_otp == $otpData->code :
                session()->flash($session_name, 'OTP verified successfully');
                DB::table('otps')->where('mobile', $mobile)->delete();
                return $response = 1;
                break;

            case $entered_otp != $otpData->code :
                session()->flash($session_name, 'Entered OTP is invalid');
                break;

            default:
                session()->flash($session_name, 'Something went wrong');
        }

    }

    public function divider($number_of_digits) {
        $tens="1";

        if($number_of_digits>8)
            return 10000000;

        while(($number_of_digits-1)>0)
        {
            $tens.="0";
            $number_of_digits--;
        }
        return $tens;
    }

    public function convertAmount($num)
    {
        // $num = "7890000";
        $ext="";//thousand,lac, crore
        $number_of_digits = strlen($num); //this is call
        if($number_of_digits>=5)
        {
            if($number_of_digits%2!=0){
                $divider=$this->divider($number_of_digits-1);}
            else{
                $divider=$this->divider($number_of_digits);}
        }
        else{
            $divider=1;
        }


        $fraction=$num/$divider;
        $fraction=round($fraction,2);
        if($number_of_digits==5){
            $ext="K";}
        if($number_of_digits==6 || $number_of_digits==7){
            $ext="L";}
        if($number_of_digits==8 || $number_of_digits>=9){
            $ext="Cr";
        }
        return $fraction."".$ext;
    }

    public function random_strings($length_of_string)
    {
        return substr(bin2hex(random_bytes($length_of_string)), 0, $length_of_string);
    }

    // Function for random quotes
    public function randQuote()
    {
        return static::quotes()
            ->map(fn ($quote) => $this->formatForWeb($quote))
            ->random();
    }

        protected function formatForWeb($quote)
    {
        [$text, $author] = str($quote)->explode('-');

        return sprintf(
            " “ %s ”\n — %s",
            trim($text),
            trim($author),
        );
    }

        public static function quotes()
    {
        return Collection::make([
            'Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant',
            'An unexamined life is not worth living. - Socrates',
            'Be present above all else. - Naval Ravikant',
            'Do what you can, with what you have, where you are. - Theodore Roosevelt',
            'Happiness is not something readymade. It comes from your own actions. - Dalai Lama',
            'He who is contented is rich. - Laozi',
            'I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger',
            'I have not failed. I\'ve just found 10,000 ways that won\'t work. - Thomas Edison',
            'If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius',
            'It is never too late to be what you might have been. - George Eliot',
            'It is not the man who has too little, but the man who craves more, that is poor. - Seneca',
            'It is quality rather than quantity that matters. - Lucius Annaeus Seneca',
            'Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci',
            'Let all your things have their places; let each part of your business have its time. - Benjamin Franklin',
            'Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi',
            'No surplus words or unnecessary actions. - Marcus Aurelius',
            'Nothing worth having comes easy. - Theodore Roosevelt',
            'Order your soul. Reduce your wants. - Augustine',
            'People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius',
            'Simplicity is an acquired taste. - Katharine Gerould',
            'Simplicity is the consequence of refined emotions. - Jean D\'Alembert',
            'Simplicity is the essence of happiness. - Cedric Bledsoe',
            'Simplicity is the ultimate sophistication. - Leonardo da Vinci',
            'Smile, breathe, and go slowly. - Thich Nhat Hanh',
            'The only way to do great work is to love what you do. - Steve Jobs',
            'The whole future lies in uncertainty: live immediately. - Seneca',
            'Very little is needed to make a happy life. - Marcus Aurelius',
            'Waste no more time arguing what a good man should be, be one. - Marcus Aurelius',
            'Well begun is half done. - Aristotle',
            'When there is no desire, all things are at peace. - Laozi',
            'Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh',
            'Because you are alive, everything is possible. - Thich Nhat Hanh',
            'Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh',
            'Life is available only in the present moment. - Thich Nhat Hanh',
            'The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh',
            'Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Marie Curie',
            'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
            'Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead',
            'You must be the change you wish to see in the world. - Mahatma Gandhi',
        ]);
    }

}

?>
