<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoRoom;
use App\Models\VideoTag;
use PhanAn\Remote\Remote;
use Auth;

class VideoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    /*public function saveVideo(Request $request) {

        $page_type = $request->input('page_type');

        if ($page_type === 'applicant') {

            $applicant_id = $request->input('applicant_id');
            $job_id = $request->input('job_id');
        }

        if ($page_type === 'employee') {
            $user_id = Auth::user('user')->user_id;
            $employee_id = $request->input('employee_id');
        }


        $stream_id = $request->input('stream_id');
        $local_stream_id = $request->input('local_stream_id');
        $remote_stream_id = $request->input('remote_stream_id');
        $video_type = $request->input('video_type');
        $video_url = $request->input('video_url');
        $video_id = 0;
        $media_server = "laravel.software";

        //Connect to the media server
        $remote_connection = new Remote([
            'host' => $media_server,
            'port' => 22,
            'username' => 'root',
            'password' => '(radio5)',
        ]);

        if ($video_type === 'local') {

            //$clean_mkv_command = '/usr/bin/ffmpeg -y -threads 4 -i /var/www/recordings/' . $stream_id . '.mkv -vcodec copy -acodec copy /var/www/recordings/' . $stream_id . '.webm';
            //Convert the mkv to webm format(tried converting to vp9 webm but only gets the first 2 seconds if run using the exec command)
            $convert_to_webm_command = 'ffmpeg -y -i /var/www/recordings/' . $stream_id . '.mkv -c:v copy -b:v 1M -c:a libvorbis /var/www/recordings/' . $stream_id . '.webm';

            //Run the mkv file in ffmpeg to repair it(Since erizo makes an invalid mkv file for the html5 video tag)
            $run_command = $remote_connection->exec($convert_to_webm_command);
        } else {
            //Clean the local and remote files
            $clean_local_command = 'ffmpeg -y -threads 8 -i /var/www/recordings/' . $local_stream_id . '.mkv -c:v copy -crf 10 -b:v 0 -c:a libvorbis /var/www/recordings/' . $local_stream_id . '.webm 2> /dev/null';
            $clean_remote_command = 'ffmpeg -y -threads 8 -i /var/www/recordings/' . $remote_stream_id . '.mkv -c:v copy -crf 10 -b:v 0 -c:a libvorbis /var/www/recordings/' . $remote_stream_id . '.webm 2> /dev/null';

            //Merge them side by side
            //$merge_files_command = 'ffmpeg -y -i /var/www/recordings/'.$local_stream_id.'.webm -i /var/www/recordings/'.$remote_stream_id.'.webm -filter_complex "[0:v] setpts=PTS-STARTPTS,scale=iw*2:ih [bg];[1:v] setpts=PTS-STARTPTS [fg];[bg][fg] overlay=w;amerge,pan=stereo:c0<c0+c2:c1<c1+c3" /var/www/recordings/'.$local_stream_id.'.webm';
            //$merge_files_command = 'ffmpeg -y -threads 8 -i /var/www/recordings/' . $local_stream_id . '.webm -i /var/www/recordings/' . $remote_stream_id . '.webm -filter_complex "[0:v]scale=640:480[left];[1:v]scale=640:480[right];[0:a][1:a]amerge=inputs=2[a];[left][right]hstack[out]" -map [out] -map "[a]" /var/www/recordings/' . $local_stream_id . '.webm 2> /dev/null';
            
            //Run the scripts
            $run_command = $remote_connection->exec($clean_local_command . '; ' . $clean_remote_command . '; ' . $merge_files_command);
            //$run_command = $remote_connection->exec($clean_remote_command);
            //$run_command = $remote_connection->exec($merge_files_command);
        }
        //Check if video already exists
        $video_exists = Video::where('stream_id', $stream_id)->where('video_type', $video_type)->count();

        if ($video_exists === 0) {

            if ($page_type === 'applicant') {

                $video = new Video([
                    'unique_id' => $applicant_id,
                    'user_type' => 'applicant',
                    'owner_id' => $job_id,
                    'owner_type' => 'job',
                    'stream_id' => $stream_id,
                    'video_type' => $video_type,
                    'video_url' => $video_url
                ]);

                $video->save();
                $video_id = $video->id;
                //Get all details as a JSON array

                $video_details = json_encode(array('video_url' => $video_url, 'video_id' => $video_id, 'applicant_id' => $applicant_id, 'job_id' => $job_id), JSON_FORCE_OBJECT);
            }

            if ($page_type === 'employee') {

                $video = new Video([
                    'unique_id' => $employee_id,
                    'user_type' => 'employee',
                    'owner_id' => $user_id,
                    'owner_type' => 'employee',
                    'stream_id' => $stream_id,
                    'video_type' => $video_type,
                    'video_url' => $video_url
                ]);

                $video->save();
                $video_id = $video->id;

                //Get all details as a JSON array
                $video_details = json_encode(array('video_url' => $video_url, 'video_id' => $video_id, 'employee_id' => $employee_id, 'user_id' => $user_id), JSON_FORCE_OBJECT);
            }
        }



        return $video_details;
    }*/
    
    
    public function saveVideo(Request $request) {
        
        $media_server = "laravel.software";
        //$media_server = "ubuntu-server.com";

        //Connect to the media server
        $remote_connection = new Remote([
            'host' => $media_server,
            'port' => 22,
            'username' => 'root',
            'password' => '(radio5)',
        ]);
        
        $room_type = $request->input('room_type');
        $room_name = $request->input('room_name');
        $stream = $request->input('stream');
        $rec_dir = $request->input('rec_dir');
        
        $video_extension = '.webm';
        $audio_extension = '.ogg';
        
        $video_url = $rec_dir.'/'.$stream.'-final'.$video_extension;
        $audio_url = $rec_dir.'/'.$stream.$audio_extension;
        
        $video_room = new VideoRoom([
            'room_name' => $room_name,
            'room_type' => $room_type,
            'streams' => $stream,
            'rec_dir' => $rec_dir
        ]);
        $video_room->save();
        
        $video_id = $video_room->id;
        
        $video_details = json_encode(array('video_id' => $video_id, 'video' => $video_url, 'audio' => $audio_url), JSON_FORCE_OBJECT);
        
        $convert_to_webm_command = '/opt/janus/bin/janus-pp-rec /var/www/html/recordings/'.$stream.'-video.mjr /var/www/html/recordings/'.$stream.'.webm';
        $convert_to_ogg_command = '/opt/janus/bin/janus-pp-rec /var/www/html/recordings/'.$stream.'-audio.mjr /var/www/html/recordings/'.$stream.'.ogg';        
        //$merge_webm_and_ogg_command = 'ffmpeg -i /var/www/html/recordings/' . $stream . '.webm -i /var/www/html/recordings/' . $stream . '.ogg -c:v copy -c:a libvorbis -strict experimental /var/www/html/recordings/' . $stream . '-final.webm';
        $merge_webm_and_ogg_command = 'ffmpeg -i /var/www/html/recordings/' . $stream . '.webm -i /var/www/html/recordings/' . $stream . '.ogg -c:v copy -shortest /var/www/html/recordings/' . $stream . '-final.webm';
        
        //$merge_videos = 'ffmpeg -i /var/www/html/recordings/' . $stream . '.webm -i /var/www/html/recordings/' . $stream . '.webm -i /var/www/html/recordings/' . $stream . '.webm -i /var/www/html/recordings/' . $stream . '.webm -i /var/www/html/recordings/' . $stream . '.webm -filter_complex "[0:v][1:v]hstack[top]; [2:v][3:v]hstack[bottom]; [top][bottom]vstack,format=yuv420p[v]; [0:a][1:a][2:a][3:a]amerge=inputs=4[a]" -map "[v]" -map "[a]" -ac 2 /var/www/html/recordings'.$stream.'-merged.webm';
        
        $remote_connection->exec($convert_to_webm_command.';'.$convert_to_ogg_command);
        
        $remote_connection->exec($merge_webm_and_ogg_command);
        
        //$remote_connection->exec($merge_videos);
        
        return $video_details;
    }
    
    public function deleteVideo(Request $request) {
        $video_id = $request->input('video_id');

        $media_server = "laravel.software";
        //$media_server = "ubuntu-server.com";
        
        //Connect to the media server
        $remote_connection = new Remote([
            'host' => $media_server,
            'port' => 22,
            'username' => 'root',
            'password' => '(radio5)'
        ]);
        
        $stream = VideoRoom::where('id', $video_id)->pluck('streams');
        
        $rec_dir = '/var/www/html/recordings/';
        $video_extension = '.webm';
        $audio_extension = '.ogg';
        
        $final_url = $rec_dir.$stream.'-final'.$video_extension;
        $video_url = $rec_dir.$stream.$video_extension;
        $audio_url = $rec_dir.$stream.$audio_extension;
        $video_mjr_url = $rec_dir.$stream.'-video.mjr';
        $audio_mjr_url = $rec_dir.$stream.'-audio.mjr';
        
        $delete_final = 'rm '.$final_url;
        $delete_webm = 'rm '.$video_url;
        $delete_ogg = 'rm '.$audio_url;
        $delete_video_mjr = 'rm '.$video_mjr_url;
        $delete_audio_mjr = 'rm '.$audio_mjr_url;
        
        $delete_video = VideoRoom::where('id', $video_id)->delete();

        $remote_connection->exec($delete_final);
        $remote_connection->exec($delete_webm);
        $remote_connection->exec($delete_ogg);
        $remote_connection->exec($delete_video_mjr);
        $remote_connection->exec($delete_audio_mjr);
        
        return $video_id;
    }

    public function addVideoTag(Request $request) {
        
        $user_id = $request->user()->id;
        $job_id = $request->input('job_id');
        $applicant_id = $request->input('applicant_id');
        $video_id = $request->input('video_id');
        $video_status = $request->input('video_status');

        $video_status_exists = VideoTag::where('job_id', $job_id)->where('applicant_id', $applicant_id)->where('user_id', $user_id)->where('video_id', $video_id)->count();

        if ($video_status_exists === 0) {

            $new_video_status = new VideoTag([
                'user_id' => $user_id,
                'job_id' => $job_id,
                'applicant_id' => $applicant_id,
                'video_id' => $video_id,
                'tags' => $video_status
            ]);

            $new_video_status->save();

            $video_status_item = VideoTag::where('id', $new_video_status->id)->first();
        } else {
            $update_video_status = VideoTag::where('job_id', $job_id)->where('applicant_id', $applicant_id)->where('user_id', $user_id)->where('video_id', $video_id)->update([
                'tags' => $video_status
            ]);
        }

        return "true";
    }

    public function getVideoTags(Request $request) {

        $term = $request->input('term');

        $entries = VideoTag::where('tags', 'like', '%' . $term . '%')->get();
        $tags = [];


        foreach ($entries as $entry) {
            $tags_string = explode(',', $entry->tags);
            foreach ($tags_string as $string) {
                $tags[] = $string;
            }
        }

        return $tags;
    }

    //janus API
    public function saveNfoJanus(Request $request){
        if($request->input('local')) {
            $this->saveThisNfoJanus($request->input('local'));
        }
        if($request->input('remote')) {
            $this->saveThisNfoJanus($request->input('remote'));
        }
    }
    private function saveThisNfoJanus($id){
        $media_server = "laravel.software";
        //$media_server = "linux.me";

        //Connect to the media server
        $remote_connection = new Remote([
            'host' => $media_server,
            'port' => 22,
            'username' => 'root',
            'password' => '(radio5)',
        ]);

        $nfo = "echo '[" . $id . "]\nname = " . $id . "\n" .
            "date = " . date('Y-m-d H:i:s') . "\n" .
            "audio = " . $id . "-audio.mjr\n" .
            "video = " . $id . "-video.mjr' > /var/www/html/recordings/" . $id . ".nfo";
        $remote_connection->exec($nfo);
    }

    public function convertJanusVideo(Request $request){
        if($request->input('local')){
            $this->convertThisJanusVideo($request->input('local'));
        }
        if($request->input('remote')){
            $this->convertThisJanusVideo($request->input('remote'));
        }
    }
    private function convertThisJanusVideo($id){
        $media_server = "laravel.software";
        //$media_server = "linux.me";

        //Connect to the media server
        $remote_connection = new Remote([
            'host' => $media_server,
            'port' => 22,
            'username' => 'root',
            'password' => '(radio5)',
        ]);

        //convert to opus and webm
        $convert_to_audio = '/opt/janus/bin/janus-pp-rec /var/www/html/recordings/' . $id . '-audio.mjr /var/www/html/recordings/' . $id . '-audio.opus';
        $remote_connection->exec($convert_to_audio);
        $convert_to_webm = '/opt/janus/bin/janus-pp-rec /var/www/html/recordings/' . $id . '-video.mjr /var/www/html/recordings/' . $id . '-video.webm';
        $remote_connection->exec($convert_to_webm);

        $sync_audio_video = 'ffmpeg -i /var/www/html/recordings/' . $id . '-video.webm -i /var/www/html/recordings/' . $id . '-audio.opus -c:v copy -c:a libvorbis -strict experimental /var/www/html/recordings/' . $id . '.webm';
        $remote_connection->exec($sync_audio_video);

        $delete_opus = 'rm -rf /var/www/html/recordings/' . $id . '-audio.opus';
        $remote_connection->exec($delete_opus);
        $delete_webm = 'rm -rf /var/www/html/recordings/' . $id . '-video.webm';
        $remote_connection->exec($delete_webm);
    }
}
