import React from 'react';

export default function CardInformationTab({ card }) {
    let content = (
        <div class="row">
            <div class="col-xl-2"></div>
            <div class="col-xl-7 my-2">
                <div class="row">
                    <label class="col-3"></label>
                    <div class="col-9">
                        <h6 class="text-dark font-weight-bold mb-10">Card Info:</h6>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Card Type*</label>
                    <div class="col-9">
                        <p className="mt-3">{card?.card_type}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Card Number</label>
                    <div class="col-9">
                        <p className="mt-3">{card?.card_number}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Expiration Date*</label>
                    <div class="col-9">
                    <p className="mt-3">{card?.expiration_date}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">CVC*</label>
                    <div class="col-9">
                    <p className="mt-3">{card?.cvc}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Holder Name*</label>
                    <div class="col-9">
                    <p className="mt-3">{card?.holder_name}</p>
                    </div>
                </div>
            </div>
        </div>
    )

    let tab = card ? (
        <div className="tab-pane px-7" id="card_information" role="tabpanel">
           {content}
        </div>
    ) : null

    return tab
}