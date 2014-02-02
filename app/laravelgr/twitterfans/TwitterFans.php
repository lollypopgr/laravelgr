<?php namespace laravelgr\twitterfans;

use Cache, Twitter, Config;

/**
 *
 * I made a list in the Twitter Account
 * in order to have a seperate clean space
 * for Developers that are located/come from my
 * Country.
 *
 */
class TwitterFans {

    /**
     * Get the Sum of Devs from the first list
     * that includes only Country specific Devs
     *
     * @return integer
     */
    public function sumDevs(){

        if(Cache::has('sumdevs')){
            $list = Cache::get('sumdevs');
        }else{
            $list = Twitter::getLists(['screen_name'=> Config::get('site.twittername')]);
            Cache::put('sumdevs',$list,30);
        }

        return $list[0]->member_count;
    }

    /**
     * Clear cache for Sumdevs
     * @return void
     */
    public function clearCache(){
        Cache::forget('sumdevs');
    }


    /**
     * Grab all users specific from Devs-list
     * @return array of pics
     */
    public function devPics(){

        $devs = Twitter::getListMembers(['list_id'=>Config::get('site.list_id'),'slug'=>'greek-devs-laravel']);
        foreach($devs->users as $dev){
            $pics[] = $dev->profile_image_url;
        }
        shuffle($pics);
        return $pics;
    }

}