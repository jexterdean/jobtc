<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Company Detail</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-default">
                <div class="box-header">
                    <h3 class="box-title">{{ $companies->contact_person }}</h3>
                </div>
                <div class="box-body">
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Company Name
                        </div>
                        <div class="col-md-7 value">
                            {{ $companies->company_name }}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Email
                        </div>
                        <div class="col-md-7 value">
                            {{{ $companies->email or 'NA' }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Phone
                        </div>
                        <div class="col-md-7 value">
                            {{{ $companies->phone or 'NA' }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Adddress
                        </div>
                        <div class="col-md-7 value">
                            {{{ $companies->address or 'NA' }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            City
                        </div>
                        <div class="col-md-7 value">
                            {{{ $companies->city or 'NA' }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Zipcode
                        </div>
                        <div class="col-md-7 value">
                            {{{ $companies->zipcode or 'NA' }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            State
                        </div>
                        <div class="col-md-7 value">
                            {{{ $companies->state or 'NA' }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Country
                        </div>
                        <div class="col-md-7 value">
                            {{{ $companies->country }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Created at
                        </div>
                        <div class="col-md-7 value">
                            {{{ date("d M Y",strtotime($companies->created_at)) }}}
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5 name">
                            Last Updated at
                        </div>
                        <div class="col-md-7 value">
                            {{{ date("d M Y",strtotime($companies->updated_at)) }}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>