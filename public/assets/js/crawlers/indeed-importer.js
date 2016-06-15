/*
 * Indeed Importer
 * Author: Jexter Dean Buenaventura
 **/

var casper = require('casper').create({
    verbose: false,
    logLevel: 'debug',
    pageSettings: {
        loadImages: false, // The WebPage instance used by Casper will
        loadPlugins: false, // use these settings
        webSecurityEnabled: false,
        ignoreSslErrors: true,
        viewportSize: {width: 1366, height: 784}
        //userAgent: "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36"
    }
//remoteScripts: ['https://code.jquery.com/jquery-2.1.4.min.js']
});

/*Script options*/
//Job.tc Url
casper.echo(casper.cli.get('url'));
//Company who owns the jobs
casper.echo(casper.cli.get('company_id'));
//User who owns the jobs
casper.echo(casper.cli.get('user_id'));
//Where to store the applicant resumes
casper.echo(casper.cli.get('applicants_dir'));
//Job.tc email
casper.echo(casper.cli.get('jobtc_email'));
//Job.tc password
casper.echo(casper.cli.get('jobtc_password'));

var url = 'https://employers.indeed.com/m#jobs';
var linkurl = 'https://employers.indeed.com/m';
var candidateurl = 'https://employers.indeed.com/m#candidates?id=0';
var downloadurl = 'https://employers.indeed.com';
var candidatesPageArray;
var jobs = [];
var links = [];
var candidates = [];
var uniqueCandidates = [];
var found = [];
var nextLink;
//For Job
var title;
var desc;
//For Candidate
var name_str;
var name;
var first_name;
var last_name;
var email;
var email_phone_str;
var email_phone;
var phone;
var job_title_str;
var job_title;
var job;
var resume;
//For Http Validation
var token;
//For Directory

//Login to Indeed using your employer account
casper.start(url, function () {
    this.fill('form#loginform', {
        email: 'projectmanager@hdenergy.ca',
        password: '1234567890'
    }, true);
});

casper.then(function () {
    this.wait(3000, function () {
        //Check if the job table has been loaded
        if (this.exists('td.job')) {
            this.echo(this.getTitle());
        } else {
            this.echo('element does not exist');
        }
    });
});

casper.then(function () {
    this.click('.rwC');
    this.wait(3000, function () {
        //this.clickLabel('Construction Worker', 'a');
        links = this.evaluate(getJobLinks);
        this.each(links, function (self, link) {
            this.thenOpen(linkurl + link, function () {
                this.wait(3000, function () {
                    self.echo(self.getCurrentUrl());
                    this.test.assertExists('div#jD', 'Job Description Exists');
                    //this.echo(this.fetchText('div#jD'));
                    title = self.getTitle();
                    desc = self.fetchText('div#jD');
                    self.echo(title, 'INFO');
                    //self.echo(desc, 'INFO');

                    jobs.push({title: title, description: desc});

                    this.echo('Starting Ajax request');
                    this.thenOpen(casper.cli.get('url') + '/login', function () {
                        this.fill('form#login-form', {
                            email: casper.cli.raw.get('jobtc_email'),
                            password: casper.cli.raw.get('jobtc_password')
                        }, true);
                        this.echo(this.getTitle());
                    });
                    this.thenOpen(casper.cli.get('url') + '/applyToJobForm', function () {
                        var token = self.getElementAttribute('input[type="hidden"][name="_token"]', 'value');
                        var jobData = {
                            '_token': token,
                            title: title,
                            description: desc,
                            photo: '',
                            user_id: casper.cli.raw.get('user_id'),
                            company_id: casper.cli.raw.get('company_id')
                        };
                        //this.fill('form.add-job-form', jobData, true);
                        this.evaluate(function (data, url) {
                            __utils__.sendAJAX(url + '/addJobFromCrawler', 'POST', data, false);
                        }, jobData, casper.cli.get('url'));
                    });
                });
            });
        });
    });
});

casper.thenOpen(candidateurl, function () {
    this.wait(3000, function () {
        temp = this.evaluate(getPaginationLinks);
        //temp.push(candidateurl);
        candidatesPageArray = temp.reduce(function (a, b) {
            if (a.indexOf(b) < 0)
                a.push(b);
            return a;
        }, []);
    });
});


casper.then(function () {
    this.wait(3000, function () {
        candidatesPageArray.push(candidateurl);
    });
});
casper.then(function () {
    //candidatesPageArray.push(candidateurl);
    this.each(candidatesPageArray, function (self, link) {
        self.thenOpen(link, function () {
            self.wait(3000, function () {
                found = this.evaluate(getCandidateLinks);
                candidates = candidates.concat(found);
            });
        });
    });
});
casper.then(function () {
    candidates = candidates.reduce(function (a, b) {
        if (a.indexOf(b) < 0)
            a.push(b);
        return a;
    }, []);
});

casper.then(function () {
    this.each(candidates, function (self, link) {
        self.thenOpen(linkurl + link, function () {
            self.wait(3000, function () {
                self.echo(self.fetchText('h3.name'));
                self.echo(self.fetchText('a[data-element=back-job]'), 'INFO');
                self.echo(self.fetchText('div.name-plate p'), 'INFO');
                self.echo(this.getElementAttribute('a[data-element=download-resume]', 'href'), 'INFO');

                var name = self.fetchText('h3.name');

                self.echo("Name: " + name);
                //Split the email and phone
                var email_phone_str = self.fetchText('div.name-plate p');
                var email_phone = email_phone_str.split("|");
                email = email_phone[0];
                phone = email_phone[1];
                //self.echo("Email: " + email);
                //self.echo("Phone: " + phone);
                //Get Job Title
                var job_title_str = self.fetchText('a[data-element=back-job] span');
                var job_title = job_title_str.split(' ');
                job = job_title[2];
                self.echo("Job Title: " + job, 'INFO');
                resume = downloadurl + "" + this.getElementAttribute('a[data-element=download-resume]', 'href');

                casper.download(resume, 'Resume' + name.replace(/\s/g, '') + '.pdf');

                this.thenOpen(casper.cli.get('url') + '/login', function () {
                    this.wait(3000, function () {
                        this.fill('form#login-form', {
                            'email': casper.cli.raw.get('jobtc_email'),
                            'password': casper.cli.raw.get('jobtc_password')
                        }, true);
                    });
                });
                this.thenOpen(casper.cli.get('url') + '/applyToJobForm', function () {
                    token = this.getElementAttribute('input[type="hidden"][name="_token"]', 'value');

                    var candidateData = {
                        name: name,
                        email: email,
                        phone: phone,
                        job: job,
                        _token: token
                    };

                    this.echo("Response: " + JSON.stringify(candidateData), 'INFO');
                    this.evaluate(function (data, url) {
                        __utils__.sendAJAX(url + '/addApplicantFromCrawler', 'POST', data, false);
                    }, candidateData, casper.cli.get('url'));
                });
            });
        });
    });
});

casper.then(function () {
    this.echo('__END__');
    this.exit();
});

casper.on('step.error', function (err) {
    this.die("Step has failed: " + err);
});

casper.on('resource.received', function (resource) {
    "use strict";

    this.echo(resource.url);
    var url, file;
    url = resource.url;
    //file = "stats.csv";
    try {
        //this.echo("Attempting to download file " + file);
        //var fs = require('fs');
        var fs = require('fs');
        //fs.changeWorkingDirectory('E:/xampp-new/htdocs/hirefitnet/hirefitnet/public/uploads/applicants');
        fs.changeWorkingDirectory(casper.cli.raw.get('applicants_dir'));
        //this.echo(fs.workingDirectory);
        //casper.download(resource.url, fs.workingDirectory+'/'+file);
    } catch (e) {
        this.echo(e);
    }

});


casper.run();


function getJobLinks() {
    var links = document.querySelectorAll('.jobTitle');
    return Array.prototype.map.call(links, function (e) {
        return e.getAttribute('href');
    });
}

function getCandidateLinks() {
    var links = document.querySelectorAll('.candidate-link');
    return Array.prototype.map.call(links, function (e) {
        return e.getAttribute('href');
    });
}

function getPaginationLinks() {
    var links = document.querySelectorAll('.pagination a');
    return Array.prototype.map.call(links, function (e) {
        return e.getAttribute('href');
    });
}
