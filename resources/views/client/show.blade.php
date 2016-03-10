
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">Client Detail</h4>
	</div>
	<div class="modal-body">
		<div class="row">
	        <div class="col-md-12">
	            <div class="box box-solid box-primary">
	                <div class="box-header">
	                    <h3 class="box-title">{{ $client->contact_person }}</h3>
	                </div>
					<div class="box-body">
						<div class="row static-info">
							<div class="col-md-5 name">
								 Company Name
							</div>
							<div class="col-md-7 value">
								 {{ $client->company_name }} 
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 Email
							</div>
							<div class="col-md-7 value">
								 {{{ $client->email or 'NA' }}}
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 Phone
							</div>
							<div class="col-md-7 value">
								 {{{ $client->phone or 'NA' }}}
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 Adddress
							</div>
							<div class="col-md-7 value">
								 {{{ $client->address or 'NA' }}}
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 City
							</div>
							<div class="col-md-7 value">
								 {{{ $client->city or 'NA' }}}
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 Zipcode
							</div>
							<div class="col-md-7 value">
								 {{{ $client->zipcode or 'NA' }}} 
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 State
							</div>
							<div class="col-md-7 value">
								 {{{ $client->state or 'NA' }}} 
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 Country
							</div>
							<div class="col-md-7 value">
								 {{{ $countries[$client->country_id] or 'NA' }}} 
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 Created at
							</div>
							<div class="col-md-7 value">
								 {{{ date("d M Y",strtotime($client->created_at)) }}}
							</div>
						</div>
						<div class="row static-info">
							<div class="col-md-5 name">
								 Last Updated at
							</div>
							<div class="col-md-7 value">
								 {{{ date("d M Y",strtotime($client->updated_at)) }}}
							</div>
						</div>
					</div>
	            </div>
			</div>
		</div>
	</div>