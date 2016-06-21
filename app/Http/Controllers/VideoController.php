<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoTag;
use PhanAn\Remote\Remote;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    
    public function saveVideo(Request $request) {
        $applicant_id = $request->input('applicant_id');
        $job_id = $request->input('job_id');
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
            $convert_to_webm_command = 'ffmpeg -y -i /var/www/recordings/'.$stream_id. '.mkv -c:v copy -crf 10 -b:v 0 -c:a libvorbis /var/www/recordings/'.$stream_id.'.webm';
            
            //Run the mkv file in ffmpeg to repair it(Since erizo makes an invalid mkv file for the html5 video tag)
            $run_command = $remote_connection->exec($convert_to_webm_command);
            
        } else {
            //Clean the local and remote files
            $clean_local_command = 'ffmpeg -y -threads 8 -i /var/www/recordings/'.$local_stream_id. '.mkv -c:v copy -crf 10 -b:v 0 -c:a libvorbis /var/www/recordings/'.$local_stream_id.'.webm 2> /dev/null';
            $clean_remote_command = 'ffmpeg -y -threads 8 -i /var/www/recordings/'.$remote_stream_id. '.mkv -c:v copy -crf 10 -b:v 0 -c:a libvorbis /var/www/recordings/'.$remote_stream_id.'.webm 2> /dev/null';
            
            //Merge them side by side
            //$merge_files_command = 'ffmpeg -y -i /var/www/recordings/'.$local_stream_id.'.webm -i /var/www/recordings/'.$remote_stream_id.'.webm -filter_complex "[0:v] setpts=PTS-STARTPTS,scale=iw*2:ih [bg];[1:v] setpts=PTS-STARTPTS [fg];[bg][fg] overlay=w;amerge,pan=stereo:c0<c0+c2:c1<c1+c3" /var/www/recordings/'.$local_stream_id.'.webm';
            $merge_files_command = 'ffmpeg -y -threads 8 -i /var/www/recordings/'.$local_stream_id.'.webm -i /var/www/recordings/'.$remote_stream_id.'.webm -filter_complex "[0:v]scale=640:480[left];[1:v]scale=640:480[right];[0:a][1:a]amerge=inputs=2[a];[left][right]hstack[out]" -map [out] -map "[a]" /var/www/recordings/'.$local_stream_id.'.webm 2> /dev/null';
            //$merge_files_command = 'ffmpeg -y -threads 4 -i /var/www/recordings/'.$local_stream_id. '.webm -vf "[in] setpts=PTS-STARTPTS,scale=640:480, pad=2*640:480 [left];movie=/var/www/recordings/'.$remote_stream_id. '.webm, asetpts=PTS-STARTPTS,scale=640:480, fade=out:300:30:alpha=1 [right];[left][right] overlay=w" -b:v 768k /var/www/recordings/'.$local_stream_id. '.webm';
            //$merge_files_command = 'ffmpeg -y -i /var/www/recordings/'.$local_stream_id. '.webm -i /var/www/recordings/'.$remote_stream_id. '.webm -filter_complex "[0:v] setpts=PTS-STARTPTS, scale=640x480 [left];[1:v] setpts=PTS-STARTPTS, scale=640x480 [right];[left][right] overlay=shortest=1" /var/www/recordings/'.$local_stream_id. '.webm';
            
            //Run the scripts
            $run_command = $remote_connection->exec($clean_local_command.'; '.$clean_remote_command .'; '.$merge_files_command);
            //$run_command = $remote_connection->exec($clean_remote_command);
            //$run_command = $remote_connection->exec($merge_files_command);

        }
        //Check if video already exists
        $video_exists = Video::where('stream_id', $stream_id)->where('video_type', $video_type)->count();

        if ($video_exists === 0) {
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
        }

        //Get all details as a JSON array
        $video_details = json_encode(array('video_url' => $video_url, 'video_id' => $video_id, 'applicant_id' => $applicant_id, 'job_id' => $job_id), JSON_FORCE_OBJECT);

        return $video_details;
    }
    
    public function deleteVideo(Request $request) {
        $video_id = $request->input('video_id');

        $video = Video::where('id', $video_id)->pluck('video_url');

        if (file_exists($video)) {
            unlink($video);
        }

        $delete_video = Video::where('id', $video_id)->delete();

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
    
}
