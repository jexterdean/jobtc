{"changed":true,"filter":false,"title":"JobController.php","tooltip":"/app/Http/Controllers/JobController.php","value":"<?php\n\nnamespace App\\Http\\Controllers;\n\nuse Illuminate\\Http\\Request;\nuse App\\Http\\Controllers\\Controller;\nuse App\\Models\\User;\nuse App\\Models\\Profile;\nuse App\\Models\\Job;\nuse App\\Models\\Company;\nuse App\\Models\\Applicant;\nuse App\\Models\\ApplicantTag;\nuse App\\Models\\MailBox;\nuse App\\Models\\MailBoxAlias;\nuse App\\Models\\Permission;\nuse App\\Models\\PermissionRole;\nuse Auth;\nuse Redirect;\n\nclass JobController extends Controller {\n\n    /**\n     * Display a listing of the resource.\n     *\n     * @return \\Illuminate\\Http\\Response\n     */\n    public function index(Request $request) {\n        //Get logged in User\n        $user_id = $request->user()->user_id;\n\n        $users = User::find($user_id);\n\n        $jobs = Job::paginate(3);\n\n        $assets = ['jobs'];\n\n        return view('jobs.index', ['name' => $users->name, 'user_id' => $user_id, 'jobs' => $jobs, 'assets' => $assets, 'count' => 0]);\n    }\n\n    /**\n     * Show the form for creating a new resource.\n     *\n     * @return \\Illuminate\\Http\\Response\n     */\n    public function create() {\n        //\n    }\n\n    /**\n     * Store a newly created resource in storage.\n     *\n     * @param  \\Illuminate\\Http\\Request  $request\n     * @return \\Illuminate\\Http\\Response\n     */\n    public function store(Request $request) {\n\n        $user_id = Auth::user()->user_id;\n        $company_id = $request->input('company_id');\n        $title = $request->input('title');\n        $description = $request->input('description');\n\n        if ($request->hasFile('photo')) {\n            $photo = $request->file('photo');\n            $photo_save = $photo->move('assets/job/', $photo->getClientOriginalName());\n            $photo_path = $photo_save->getPathname();\n        } else {\n            $photo_path = '';\n        }\n\n\n        $job = new Job();\n\n        $job->user_id = $user_id;\n        $job->company_id = $company_id;\n        $job->title = $title;\n        $job->description = $description;\n        $job->photo = $photo_path;\n        $job->save();\n\n        return Redirect::to('job/' . $job->id)->withSuccess('Job added successfully!!');\n    }\n\n    /**\n     * Display the specified resource.\n     *\n     * @param  int  $id\n     * @return \\Illuminate\\Http\\Response\n     */\n    public function show($id) {\n\n        $user_id = Auth::user('user')->user_id;\n\n        $job = Job::with('applicants')->where('id', $id)->first();\n\n        $applicants = Applicant::with(['tags' => function ($query) {\n                        $query->orderBy('created_at', 'desc');\n                    }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(5);\n        \n    $user_profile_role = Profile::where('user_id', $user_id)\n                ->where('company_id', $job->company_id)\n                ->first();\n\n        $permissions_list = [];\n\n        $permissions_role = PermissionRole::with('permission')\n                ->where('company_id', $job->company_id)\n                ->where('role_id', $user_profile_role->role_id)\n                ->get();\n\n        foreach ($permissions_role as $role) {\n            array_push($permissions_list, $role->permission_id);\n        }\n\n        $module_permissions = Permission::whereIn('id', $permissions_list)->get();\n\n        $assets = ['jobs'];\n\n        return view('jobs.show', [\n            'job' => $job,\n            'applicants' => $applicants,\n            'module_permissions' => $module_permissions,\n            'assets' => $assets,\n            'count' => 0\n        ]);\n    }\n\n    /**\n     * Show the form for editing the specified resource.\n     *\n     * @param  int  $id\n     * @return \\Illuminate\\Http\\Response\n     */\n    public function edit($id) {\n        $job = Job::where('id', $id)->first();\n\n        return view('forms.editJobForm', ['job' => $job]);\n    }\n\n    /**\n     * Update the specified resource in storage.\n     *\n     * @param  \\Illuminate\\Http\\Request  $request\n     * @param  int  $id\n     * @return \\Illuminate\\Http\\Response\n     */\n    public function update(Request $request, $id) {\n        //$user_id = $request->user()->user_id;\n        //$job_id = $request->input('job_id');\n        $title = $request->input('title');\n        $description = $request->input('description');\n\n        if ($request->hasFile('photo')) {\n            $photo = $request->file('photo');\n            $photo_save = $photo->move('assets/job/', $photo->getClientOriginalName());\n            $photo_path = $photo_save->getPathname();\n        } else {\n            $photo_path = Job::where('id', $id)->pluck('photo');\n        }\n\n        $job = Job::find($id);\n        $job->title = $title;\n        $job->description = $description;\n        $job->photo = $photo_path;\n        $job->save();\n\n        $message = 'Job Updated';\n        return $message;\n    }\n\n    public function updateJob(Request $request, $id) {\n        $user_id = $request->user()->user_id;\n        $job_id = $request->input('job_id');\n        $title = $request->input('title');\n        $description = $request->input('description');\n\n        if ($request->hasFile('photo')) {\n            $photo = $request->file('photo');\n            $photo_save = $photo->move('assets/job/', $photo->getClientOriginalName());\n            $photo_path = $photo_save->getPathname();\n        } else {\n            $photo_path = Job::where('id', $id)->pluck('photo');\n        }\n\n        $job = Job::find($id);\n        $job->title = $title;\n        $job->description = $description;\n        $job->photo = $photo_path;\n        $job->save();\n\n        $message = 'Job Updated';\n        return $message;\n    }\n\n    /**\n     * Remove the specified resource from storage.\n     *\n     * @param  int  $id\n     * @return \\Illuminate\\Http\\Response\n     */\n    public function destroy($id) {\n        $user_id = Auth::user()->user_id;\n\n        $job = Job::find($id);\n        $job->delete();\n\n        $message = \"Job Deleted\";\n\n        return $message;\n    }\n\n    /* For Job Posting Dashboard */\n\n    public function getJobs(Request $request) {\n        if (Auth::check(\"user\") || Auth::viaRemember(\"user\")) {\n\n            //Get logged in User\n            $user_id = $request->user()->id;\n\n            $users = User::find($user_id);\n\n            $user_info = User::where('id', $user_id)->with('profile', 'evaluation')->first();\n\n            $agent = new Agent(array(), $request->header('User-Agent'));\n\n            if ($agent->isMobile()) {\n                $is_mobile = 'true';\n                $jobs = Job::with('applicants')->paginate(1);\n            } else {\n                $is_mobile = 'false';\n                $jobs = Job::with('applicants')->paginate(3);\n            }\n            return view('jobs', ['name' => $users->first_name, 'user' => $users->user_type, 'user_info' => $user_info, 'user_id' => $user_id, 'jobs' => $jobs, 'is_mobile' => $is_mobile, 'count' => 0]);\n        } else if (Auth::check(\"applicant\") || Auth::viaRemember(\"applicant\")) {\n            return redirect()->route('a', [Auth::user(\"applicant\")->id]);\n        } else {\n            return view('home');\n        }\n    }\n\n    public function addJobForm(Request $request) {\n        return view('templates.forms.addJobForm');\n    }\n\n    public function getEditJobForm(Request $request, $id) {\n\n        $job = Job::where('id', $id)->first();\n\n        return view('templates.forms.editJobForm', ['job' => $job]);\n    }\n\n    public function addJob(Request $request) {\n\n        $user_id = $request->user()->id;\n\n        $title = $request->input('title');\n        $description = $request->input('description');\n\n        if ($request->hasFile('photo')) {\n            $photo = $request->file('photo');\n            $photo_save = $photo->move('uploads/jobs/' . $user_id, $photo->getClientOriginalName());\n            $photo_path = $photo_save->getPathname();\n        } else {\n            $photo_path = 'uploads/default-avatar.jpg';\n        }\n\n        $job = new Job([\n            'title' => $title,\n            'user_id' => $user_id,\n            'description' => $description,\n            'photo' => $photo_path\n        ]);\n\n        $job->save();\n\n        $message = \"Job Added\";\n        return $message;\n    }\n\n    public function editJob(Request $request) {\n\n        $user_id = $request->user()->id;\n        $job_id = $request->input('job_id');\n        $title = $request->input('title');\n        $description = htmlspecialchars($request->input('description'));\n\n        if ($request->hasFile('photo')) {\n            $photo = $request->file('photo');\n            $photo_save = $photo->move('uploads/jobs/' . $user_id, $photo->getClientOriginalName());\n            $photo_path = $photo_save->getPathname();\n        } else {\n            $photo_path = Job::where('id', $job_id)->pluck('photo');\n        }\n\n\n        Job::where('id', $job_id)->update([\n            'title' => $title,\n            'description' => $description,\n            'photo' => $photo_path,\n        ]);\n\n        $message = 'Job Updated';\n        return $message;\n    }\n\n    public function deleteJob(Request $request) {\n\n        $job_id = $request->input('job_id');\n\n        Job::where('id', $job_id)->delete();\n\n        $message = 'Job Deleted';\n        return $message;\n    }\n\n    /* For Single Job Posting */\n\n    public function getJobPosting(Request $request, $id) {\n\n        /* $job_posting = Job::with('applicants')->where('id', $id)->first();\n          if ($job_posting !== NULL) {\n\n\n          $applicants = Applicant::with(['status' => function ($query) {\n          $query->orderBy('created_at', 'desc');\n          }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(5);\n\n          return view('templates.show.jobPost', ['job' => $job_posting, 'applicants' => $applicants, 'count' => 0]);\n          } else {\n\n          return redirect()->route('home');\n          } */\n\n        $job = Job::with('applicants')->where('id', $id)->first();\n\n        $assets = ['jobs'];\n\n\n        return view('jobs.show', [\n            'job' => $job,\n            'assets' => $assets,\n            'count' => 0\n        ]);\n    }\n\n    public function getApplyToJobForm(Request $request) {\n        return view('forms.applyToJobForm');\n    }\n\n    public function applyToJob(Request $request) {\n\n        $job_id = $request->input('job_id');\n        $name = $request->input('name');\n        $email = $request->input('email');\n        $phone = $request->input('phone');\n        $date = date('Y-m-d h:i:s', time());\n        $username = strtolower(preg_replace('/\\s+/', '', $name) . '@' . $_SERVER['SERVER_NAME']);\n        $password = $request->input('password');\n        $remember_token = $request->input('remember');\n\n        if ($request->hasFile('photo')) {\n            $photo = $request->file('photo');\n            $photo_save = $photo->move('assets/applicant/photos/', $photo->getClientOriginalName());\n            $photo_path = $photo_save->getPathname();\n        } else {\n            $photo_path = 'assets/user/avatar.png';\n        }\n\n        if ($request->hasFile('resume')) {\n            $resume = $request->file('resume');\n            $resume_save = $resume->move('assets/applicant/resumes/', $resume->getClientOriginalName());\n            $resume_path = $resume_save->getPathname();\n        } else {\n            $resume_path = 'assets/applicant/';\n        }\n\n        $applicant = new Applicant([\n            'job_id' => $job_id,\n            'name' => $name,\n            'email' => $email,\n            'phone' => $phone,\n            'photo' => $photo_path,\n            'resume' => $resume_path,\n            'password' => bcrypt($password)\n        ]);\n\n        if ($name !== '' || $email !== '') {\n            $applicant->save();\n            $message = \"Application Submitted\";\n\n            Auth::loginUsingId(\"applicant\", $applicant->id);\n        } else {\n            $message = \"Application Denied\";\n        }\n\n        /* Switch to postfix database here */\n        //Create a temporary mailbox for applicant\n\n        /* $mailbox = new MailBox([\n          'username' => $username,\n          'password' => preg_replace('/\\s+/', '', shell_exec(\"doveadm pw -s SHA512-CRYPT -p \" . $password)),\n          //'password' => '',\n          'name' => $username,\n          'maildir' => $_SERVER['SERVER_NAME'] . '/' . $username,\n          'quota' => 0,\n          'local_part' => $username,\n          'domain' => $_SERVER['SERVER_NAME'],\n          'created' => $date,\n          'modified' => '',\n          'active' => 1\n          ]);\n\n          $mailbox->save();\n\n          //Create alias to map to itself\n          $mailboxalias = new MailBoxAlias([\n          'address' => $username . '@' . $_SERVER['SERVER_NAME'],\n          'goto' => $username . '@' . $_SERVER['SERVER_NAME'],\n          'domain' => $_SERVER['SERVER_NAME'],\n          'created' => $date,\n          'modified' => '',\n          'active' => 1\n          ]);\n\n          $mailboxalias->save(); */\n\n        //return $message; \n\n        return $applicant->id;\n    }\n\n    /* Get Applicants */\n\n    public function getJobApplicants(Request $request, $id) {\n\n        $agent = new Agent(array(), $request->header('User-Agent'));\n\n        if ($agent->isMobile()) {\n            $applicants = Applicant::with(['tags' => function ($query) {\n                            $query->orderBy('created_at', 'desc');\n                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->get();\n\n            return view('templates.show.applicantListMobile', ['applicants' => $applicants, 'count' => 0]);\n        } else {\n            $applicants = Applicant::with(['tags' => function ($query) {\n                            $query->orderBy('created_at', 'desc');\n                        }])->where('job_id', $id)->orderBy('created_at', 'desc')->paginate(5);\n\n            return view('templates.show.applicantList', ['applicants' => $applicants, 'count' => 0]);\n        }\n    }\n\n    /* For Tags */\n\n    public function addTag(Request $request) {\n        $user_id = $request->user()->user_id;\n        $job_id = $request->input('job_id');\n        $applicant_id = $request->input('applicant_id');\n        $tags = $request->input('tags');\n\n        $tag_exists = ApplicantTag::where('job_id', $job_id)->where('applicant_id', $applicant_id)->where('user_id', $user_id)->count();\n\n        if ($tag_exists === 0) {\n\n            $new_tag = new ApplicantTag([\n                'user_id' => $user_id,\n                'job_id' => $job_id,\n                'applicant_id' => $applicant_id,\n                'tags' => $tags\n            ]);\n\n            $new_tag->save();\n\n            $tag_item = ApplicantTag::where('id', $new_tag->id)->first();\n        } else {\n            $update_tag = ApplicantTag::where('job_id', $job_id)->where('applicant_id', $applicant_id)->where('user_id', $user_id)->update([\n                'tags' => $tags\n            ]);\n\n            $tag_item = ApplicantTag::where('id', $update_tag->id)->first();\n        }\n\n        return $tag_item->tags;\n    }\n\n    /* Get all tags made by all users */\n\n    public function getTags(Request $request) {\n\n        $term = $request->input('term');\n\n        $entries = ApplicantTag::where('tags', 'like', '%' . $term . '%')->get();\n        $tags = [];\n\n        foreach ($entries as $entry) {\n            $tags_string = explode(',', $entry->tags);\n            foreach ($tags_string as $string) {\n                $tags[] = $string;\n            }\n        }\n\n        return $tags;\n    }\n\n    public function saveJobNotes(Request $request) {\n        $job_id = $request->input('job_id');\n        $notes = $request->input('notes');\n\n        $job = Job::where('id', $job_id);\n        $job->update([\n            'notes' => $notes\n        ]);\n\n        return \"true\";\n    }\n\n    public function checkApplicantDuplicateEmail(Request $request) {\n\n        $email = $request->input('email');\n\n        $applicant = Applicant::where('email', $email)->count();\n\n        if ($applicant > 0) {\n\n            //There is a duplicate, return false to the jquery Validator\n            return \"false\";\n        } else {\n            //No Duplicates, return true to the jquery Validator\n            return \"true\";\n        }\n    }\n\n    public function addJobFormCompany() {\n        return view('forms.addJobForm');\n    }\n\n    public function addJobCompany(Request $request) {\n\n        $user_id = Auth::user('user')->user_id;\n        $company_id = $request->input('company_id');\n        $job_title = $request->input('job_title');\n\n        $job = new Job();\n        $job->user_id = $user_id;\n        $job->company_id = $company_id;\n        $job->title = $job_title;\n        $job->save();\n\n\n        return view('jobs.partials._newjob', [\n            'job' => $job,\n            'company_id' => $company_id\n        ]);\n    }\n\n}\n","undoManager":{"mark":98,"position":100,"stack":[[{"start":{"row":7,"column":4},"end":{"row":7,"column":5},"action":"remove","lines":["a"],"id":16}],[{"start":{"row":7,"column":4},"end":{"row":7,"column":5},"action":"insert","lines":["A"],"id":17}],[{"start":{"row":7,"column":5},"end":{"row":7,"column":6},"action":"insert","lines":["p"],"id":18}],[{"start":{"row":7,"column":6},"end":{"row":7,"column":7},"action":"insert","lines":["p"],"id":19}],[{"start":{"row":7,"column":7},"end":{"row":7,"column":8},"action":"insert","lines":["\\"],"id":20}],[{"start":{"row":7,"column":8},"end":{"row":7,"column":9},"action":"insert","lines":["M"],"id":21}],[{"start":{"row":7,"column":9},"end":{"row":7,"column":10},"action":"insert","lines":["o"],"id":22}],[{"start":{"row":7,"column":10},"end":{"row":7,"column":11},"action":"insert","lines":["d"],"id":23}],[{"start":{"row":7,"column":11},"end":{"row":7,"column":12},"action":"insert","lines":["e"],"id":24}],[{"start":{"row":7,"column":12},"end":{"row":7,"column":13},"action":"insert","lines":["l"],"id":25}],[{"start":{"row":7,"column":13},"end":{"row":7,"column":14},"action":"insert","lines":["s"],"id":26}],[{"start":{"row":7,"column":14},"end":{"row":7,"column":15},"action":"insert","lines":["\\"],"id":27}],[{"start":{"row":7,"column":15},"end":{"row":7,"column":16},"action":"insert","lines":["P"],"id":28}],[{"start":{"row":7,"column":16},"end":{"row":7,"column":17},"action":"insert","lines":["r"],"id":29}],[{"start":{"row":7,"column":17},"end":{"row":7,"column":18},"action":"insert","lines":["o"],"id":30}],[{"start":{"row":7,"column":18},"end":{"row":7,"column":19},"action":"insert","lines":["f"],"id":31}],[{"start":{"row":7,"column":19},"end":{"row":7,"column":20},"action":"insert","lines":["i"],"id":32}],[{"start":{"row":7,"column":20},"end":{"row":7,"column":21},"action":"insert","lines":["l"],"id":33}],[{"start":{"row":7,"column":21},"end":{"row":7,"column":22},"action":"insert","lines":["e"],"id":34}],[{"start":{"row":7,"column":22},"end":{"row":7,"column":23},"action":"insert","lines":[";"],"id":35}],[{"start":{"row":13,"column":28},"end":{"row":14,"column":0},"action":"insert","lines":["",""],"id":36}],[{"start":{"row":14,"column":0},"end":{"row":14,"column":1},"action":"insert","lines":["u"],"id":37}],[{"start":{"row":14,"column":1},"end":{"row":14,"column":2},"action":"insert","lines":["s"],"id":38}],[{"start":{"row":14,"column":2},"end":{"row":14,"column":3},"action":"insert","lines":["e"],"id":39}],[{"start":{"row":14,"column":3},"end":{"row":14,"column":4},"action":"insert","lines":[" "],"id":40}],[{"start":{"row":14,"column":4},"end":{"row":14,"column":5},"action":"insert","lines":["A"],"id":41}],[{"start":{"row":14,"column":5},"end":{"row":14,"column":6},"action":"insert","lines":["p"],"id":42}],[{"start":{"row":14,"column":6},"end":{"row":14,"column":7},"action":"insert","lines":["p"],"id":43}],[{"start":{"row":14,"column":7},"end":{"row":14,"column":8},"action":"insert","lines":["\\"],"id":44}],[{"start":{"row":14,"column":8},"end":{"row":14,"column":9},"action":"insert","lines":["P"],"id":45}],[{"start":{"row":14,"column":9},"end":{"row":14,"column":10},"action":"insert","lines":["e"],"id":46}],[{"start":{"row":14,"column":10},"end":{"row":14,"column":11},"action":"insert","lines":["r"],"id":47}],[{"start":{"row":14,"column":11},"end":{"row":14,"column":12},"action":"insert","lines":["m"],"id":48}],[{"start":{"row":14,"column":12},"end":{"row":14,"column":13},"action":"insert","lines":["i"],"id":49}],[{"start":{"row":14,"column":13},"end":{"row":14,"column":14},"action":"insert","lines":["s"],"id":50}],[{"start":{"row":14,"column":14},"end":{"row":14,"column":15},"action":"insert","lines":["s"],"id":51}],[{"start":{"row":14,"column":15},"end":{"row":14,"column":16},"action":"insert","lines":["i"],"id":52}],[{"start":{"row":14,"column":16},"end":{"row":14,"column":17},"action":"insert","lines":["o"],"id":53}],[{"start":{"row":14,"column":17},"end":{"row":14,"column":18},"action":"insert","lines":["n"],"id":54}],[{"start":{"row":14,"column":17},"end":{"row":14,"column":18},"action":"remove","lines":["n"],"id":55}],[{"start":{"row":14,"column":16},"end":{"row":14,"column":17},"action":"remove","lines":["o"],"id":56}],[{"start":{"row":14,"column":15},"end":{"row":14,"column":16},"action":"remove","lines":["i"],"id":57}],[{"start":{"row":14,"column":14},"end":{"row":14,"column":15},"action":"remove","lines":["s"],"id":58}],[{"start":{"row":14,"column":13},"end":{"row":14,"column":14},"action":"remove","lines":["s"],"id":59}],[{"start":{"row":14,"column":12},"end":{"row":14,"column":13},"action":"remove","lines":["i"],"id":60}],[{"start":{"row":14,"column":11},"end":{"row":14,"column":12},"action":"remove","lines":["m"],"id":61}],[{"start":{"row":14,"column":10},"end":{"row":14,"column":11},"action":"remove","lines":["r"],"id":62}],[{"start":{"row":14,"column":9},"end":{"row":14,"column":10},"action":"remove","lines":["e"],"id":63}],[{"start":{"row":14,"column":8},"end":{"row":14,"column":9},"action":"remove","lines":["P"],"id":64}],[{"start":{"row":14,"column":8},"end":{"row":14,"column":9},"action":"insert","lines":["M"],"id":65}],[{"start":{"row":14,"column":9},"end":{"row":14,"column":10},"action":"insert","lines":["o"],"id":66}],[{"start":{"row":14,"column":10},"end":{"row":14,"column":11},"action":"insert","lines":["d"],"id":67}],[{"start":{"row":14,"column":11},"end":{"row":14,"column":12},"action":"insert","lines":["e"],"id":68}],[{"start":{"row":14,"column":12},"end":{"row":14,"column":13},"action":"insert","lines":["l"],"id":69}],[{"start":{"row":14,"column":13},"end":{"row":14,"column":14},"action":"insert","lines":["s"],"id":70}],[{"start":{"row":14,"column":14},"end":{"row":14,"column":15},"action":"insert","lines":["\\"],"id":71}],[{"start":{"row":14,"column":15},"end":{"row":14,"column":16},"action":"insert","lines":["P"],"id":72}],[{"start":{"row":14,"column":16},"end":{"row":14,"column":17},"action":"insert","lines":["e"],"id":73}],[{"start":{"row":14,"column":17},"end":{"row":14,"column":18},"action":"insert","lines":["r"],"id":74}],[{"start":{"row":14,"column":18},"end":{"row":14,"column":19},"action":"insert","lines":["m"],"id":75}],[{"start":{"row":14,"column":19},"end":{"row":14,"column":20},"action":"insert","lines":["i"],"id":76}],[{"start":{"row":14,"column":20},"end":{"row":14,"column":21},"action":"insert","lines":["s"],"id":77}],[{"start":{"row":14,"column":21},"end":{"row":14,"column":22},"action":"insert","lines":["s"],"id":78}],[{"start":{"row":14,"column":22},"end":{"row":14,"column":23},"action":"insert","lines":["i"],"id":79}],[{"start":{"row":14,"column":23},"end":{"row":14,"column":24},"action":"insert","lines":["o"],"id":80}],[{"start":{"row":14,"column":24},"end":{"row":14,"column":25},"action":"insert","lines":["n"],"id":81}],[{"start":{"row":14,"column":25},"end":{"row":14,"column":26},"action":"insert","lines":["R"],"id":82}],[{"start":{"row":14,"column":26},"end":{"row":14,"column":27},"action":"insert","lines":["o"],"id":83}],[{"start":{"row":14,"column":27},"end":{"row":14,"column":28},"action":"insert","lines":["l"],"id":84}],[{"start":{"row":14,"column":28},"end":{"row":14,"column":29},"action":"insert","lines":["e"],"id":85}],[{"start":{"row":14,"column":29},"end":{"row":14,"column":30},"action":"insert","lines":[";"],"id":86}],[{"start":{"row":104,"column":38},"end":{"row":104,"column":41},"action":"remove","lines":["$id"],"id":87},{"start":{"row":104,"column":38},"end":{"row":104,"column":54},"action":"insert","lines":["$job->company_id"]}],[{"start":{"row":13,"column":28},"end":{"row":14,"column":0},"action":"insert","lines":["",""],"id":94}],[{"start":{"row":14,"column":0},"end":{"row":14,"column":1},"action":"insert","lines":["u"],"id":95}],[{"start":{"row":14,"column":1},"end":{"row":14,"column":2},"action":"insert","lines":["s"],"id":96}],[{"start":{"row":14,"column":2},"end":{"row":14,"column":3},"action":"insert","lines":["e"],"id":97}],[{"start":{"row":14,"column":3},"end":{"row":14,"column":4},"action":"insert","lines":[" "],"id":98}],[{"start":{"row":14,"column":4},"end":{"row":14,"column":5},"action":"insert","lines":["A"],"id":99}],[{"start":{"row":14,"column":5},"end":{"row":14,"column":6},"action":"insert","lines":["p"],"id":100}],[{"start":{"row":14,"column":6},"end":{"row":14,"column":7},"action":"insert","lines":["p"],"id":101}],[{"start":{"row":14,"column":7},"end":{"row":14,"column":8},"action":"insert","lines":["\\"],"id":102}],[{"start":{"row":14,"column":8},"end":{"row":14,"column":9},"action":"insert","lines":["M"],"id":103}],[{"start":{"row":14,"column":9},"end":{"row":14,"column":10},"action":"insert","lines":["o"],"id":104}],[{"start":{"row":14,"column":10},"end":{"row":14,"column":11},"action":"insert","lines":["d"],"id":105}],[{"start":{"row":14,"column":11},"end":{"row":14,"column":12},"action":"insert","lines":["e"],"id":106}],[{"start":{"row":14,"column":12},"end":{"row":14,"column":13},"action":"insert","lines":["l"],"id":107}],[{"start":{"row":14,"column":13},"end":{"row":14,"column":14},"action":"insert","lines":["s"],"id":108}],[{"start":{"row":14,"column":14},"end":{"row":14,"column":15},"action":"insert","lines":["\\"],"id":109}],[{"start":{"row":14,"column":15},"end":{"row":14,"column":16},"action":"insert","lines":["P"],"id":110}],[{"start":{"row":14,"column":16},"end":{"row":14,"column":17},"action":"insert","lines":["e"],"id":111}],[{"start":{"row":14,"column":17},"end":{"row":14,"column":18},"action":"insert","lines":["r"],"id":112}],[{"start":{"row":14,"column":18},"end":{"row":14,"column":19},"action":"insert","lines":["m"],"id":113}],[{"start":{"row":14,"column":19},"end":{"row":14,"column":20},"action":"insert","lines":["i"],"id":114}],[{"start":{"row":14,"column":20},"end":{"row":14,"column":21},"action":"insert","lines":["s"],"id":115}],[{"start":{"row":14,"column":21},"end":{"row":14,"column":22},"action":"insert","lines":["s"],"id":116}],[{"start":{"row":14,"column":22},"end":{"row":14,"column":23},"action":"insert","lines":["i"],"id":117}],[{"start":{"row":14,"column":23},"end":{"row":14,"column":24},"action":"insert","lines":["o"],"id":118}],[{"start":{"row":14,"column":24},"end":{"row":14,"column":25},"action":"insert","lines":["n"],"id":119}],[{"start":{"row":14,"column":25},"end":{"row":14,"column":26},"action":"insert","lines":[";"],"id":120}],[{"start":{"row":7,"column":23},"end":{"row":7,"column":24},"action":"insert","lines":["|"],"id":121}],[{"start":{"row":7,"column":23},"end":{"row":7,"column":24},"action":"remove","lines":["|"],"id":122}]]},"ace":{"folds":[],"scrolltop":300,"scrollleft":0,"selection":{"start":{"row":62,"column":45},"end":{"row":62,"column":45},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":20,"state":"php-start","mode":"ace/mode/php"}},"timestamp":1467703096080}