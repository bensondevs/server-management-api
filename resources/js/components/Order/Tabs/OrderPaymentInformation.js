import React from 'react';

export default function OrderPaymentInformation({ payment }) {
    console.log(payment?.vendor_api_response)

    let informations = (
        <div className="row">
            <div className="col-xl-2"></div>
            <div className="col-xl-7 my-2">
                <div className="row">
                    <label className="col-3"></label>
                    <div className="col-9">
                        <h6 className="text-dark font-weight-bold mb-10">Payment Info:</h6>
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-form-label col-3 text-lg-right text-left">Payment Method</label>
                    <div className="col-9">
                        <p className="mt-3">{payment?.payment_method}</p>
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-form-label col-3 text-lg-right text-left">Amount</label>
                    <div className="col-9">
                        <p className="mt-3">{payment?.amount}</p>
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-form-label col-3 text-lg-right text-left">Status</label>
                    <div className="col-9">
                    <p className="mt-3">{payment?.status}</p>
                    </div>
                </div>
                <div className="form-group row">
                    <label className="col-form-label col-3 text-lg-right text-left">Payment Created at</label>
                    <div className="col-9">
                    <p className="mt-3">{payment?.created_at}</p>
                    </div>
                </div>
            </div>
        </div>
    )

    let buttons = (
        <div className="row">
            <div className="col-xl-2"></div>
            <div className="col-xl-7">
                <div className="row">
                    <div className="col-3"></div>
                    <div className="col-9">
                        <button 
                            type="submit" 
                            className="btn btn-light-success font-weight-bold">
                            Pay Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    )
    let content = (
        <div>
            {informations}
            {(payment?.status == 'unpaid') || (! payment) ? buttons : null}
        </div>
    )

    return (
        <div className="tab-pane show px-7" id="payment_information" role="tabpanel">
            {content}
        </div>
    );
}