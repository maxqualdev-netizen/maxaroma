<h3 class="cart-hd">
    Agent Information
     <a href="javascript:void(0);" onclick="edit_checkout_step('agent');">
        <svg class="sv-edit" aria-hidden="true" role="img" width="16" height="16">
            <use href="#sv-edit" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-edit"></use>
        </svg>
    </a>
</h3>
<span class="ck-editlink d-none" id="agent-edit-link">
    <a href="javascript:void(0);" onclick="edit_checkout_step('agent');" class="prime-link ulink" title="Edit">Edit</a></span>
<div class="ck-leftsec-info d-none" id="agent-form-section">
    <div class="pt-3 pt-md-4">
        <div class="row">
            <div class="col-12">
                <div class="agent-info-hd agent-info-group d-flex">
                    <div class="sub-hd1 aig-left">Salesperson</div>
                    <div class="sub-hd1 aig-right">(%)</div>
                </div>
                <form id="frmCheckOut" name="frmCheckOut">
                    <div class="form-group mb-md-3 mb-2">
                        <input type="hidden" name="totagents" id="totagents" value="{{ count($salesAgentsForCommission) }}">
                        <input type="hidden" name="cntagents" id="cntagents" value="{{ count($salesAgentsForCommission) }}">
                        <input type="hidden" name="editCurrentPts" id="editCurrentPts" value="">
                        @if(count($salesAgentsForCommission) > 0)
                            @foreach($salesAgentsForCommission as $index => $salesAgents)
                            <div class="form-group agent-info-group d-flex justify-content-center">
                                <div class="aig-left">
                                    <input type="hidden" name="salespersonuser{{$index + 1}}" id="salespersonuser{{$index + 1}}" class="form-control" value="{{ $salesAgents['sales_person_id']}}" />
                                    <input type="text" name="salesperson{{$index + 1}}" id="salesperson{{$index + 1}}" class="form-control" onblur="javascript:check_salesuser(this.value, '{{$index + 1}}');"  onkeyup="javascript:load_salesperson_data(this.value, '{{$index + 1}}');" autocomplete="off" value="{{ $salesAgents['sales_person_email']}}" />
                                    <ul id="search_result{{$index + 1}}"></ul>

                                </div>
                                <div class="aig-right d-flex">
                                    <input type="text" name="salesperson{{$index + 1}}_per{{$index + 1}}" id="salesperson{{$index + 1}}_per{{$index + 1}}" class="form-control commission-input" onblur="javascript: checkChangedCommissionValues({{$index + 1}});" value="{{ $salesAgents['sales_person_per']}}" inputmode="decimal" />
                                    <input type="hidden" name="salesperson{{$index + 1}}_fix{{$index + 1}}" id="salesperson{{$index + 1}}_fix{{$index + 1}}" value='0' />
                                    <div class="aig-icon d-flex pl-2  align-items-center">
                                        <a href="javascript:void(0);" onclick="javascript:deleteSalesCommission({{$index + 1}});">
                                            <svg class="sv-trash vam" aria-hidden="true" role="img" width="18" height="18">
                                                <use href="#sv-trash" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#sv-trash"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <span class="frmerror mt" id="error_salesperson{{$index + 1}}"></span>
                            </div>
                            @endforeach
                        @endif
                        <div id="addAgent"></div>

                        <div class="d-flex ag-info-btn mb-4">
                            <span class="frmerror mt" id="error_commission"></span>
                        </div>
                        <div class="d-flex ag-info-btn justify-content-center">
                            <a class="btn btn-secondary mr-2" href="javascript:void(0);" onclick="javascript:addAgent();" id="addAgentDiv"><span>Add Agent</span></a>
                            <a href="javascript:void(0);" onclick="save_storeuser_detail();" class="btn btn-primary" title="Save &amp; Continue">SAVE &amp; Continue</a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
@if(count($salesAgentsForCommission) > 0)
<div class="left-sec-infodtl" id="agent-info-section">
    @foreach($salesAgentsForCommission as $index => $salesAgents)
    <div class="left-infodtl-inner @if($index==0)  @endif mt-md-1 mt-1" id="agent-filled-section">{{ $salesAgents['sales_person_email']}} ({{ $salesAgents['sales_person_per']}} %)</div>
    @endforeach
</div>
@endif