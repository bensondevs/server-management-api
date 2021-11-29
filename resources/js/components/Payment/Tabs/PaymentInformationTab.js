import React from 'react';

export default function PaymentInformationTab({ payment }) {
    let informations = (
        <div class="row">
            <div class="col-xl-2"></div>
            <div class="col-xl-7 my-2">
                <div class="row">
                    <label class="col-3"></label>
                    <div class="col-9">
                        <h6 class="text-dark font-weight-bold mb-10">Payment Info:</h6>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Payment Method*</label>
                    <div class="col-9">
                        <p className="mt-3">{payment.payment_method}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Amount</label>
                    <div class="col-9">
                        <p className="mt-3">{payment.amount}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Status*</label>
                    <div class="col-9">
                    <p className="mt-3">{payment.status}</p>
                    </div>
                </div>
            </div>
        </div>
    )
    let buttons = (
        <div class="row">
            <div class="col-xl-2"></div>
            <div class="col-xl-7">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-9">
                        <button 
                            type="submit" 
                            class="btn btn-light-primary font-weight-bold">
                            Save changes
                        </button>
                        <a href="#" class="btn btn-clean font-weight-bold">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    )
    let content = (
        <div>
            {informations}
            {buttons}
        </div>
    )

    return (
        <div className="tab-pane show px-7 active" id="order_information" role="tabpanel">
            {content}
        </div>
    );
}