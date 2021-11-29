import React from 'react';

export default function OrderServicePlan({ servicePlan }) {
    return (
        <div className="tab-pane px-7" id="service_plans" role="tabpanel">
            <div className="row">
                <div className="col-xl-2"></div>
                <div className="col-xl-7 my-2">
                    <div className="row">
                        <label className="col-3"></label>
                        <div className="col-9">
                            <h6 className="text-dark font-weight-bold mb-10">Service Plan:</h6>
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-form-label col-3 text-lg-right text-left">Currency*</label>
                        <div className="col-9">
                            <p className="mt-3">{servicePlan?.currency}</p>
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-form-label col-3 text-lg-right text-left">Subscription Fee</label>
                        <div className="col-9">
                            <p className="mt-3">{servicePlan?.subscription_fee}</p>
                        </div>
                    </div>
                    <div className="form-group row">
                        <label className="col-form-label col-3 text-lg-right text-left">Duration*</label>
                        <div className="col-9">
                        <p className="mt-3">{servicePlan?.time_quantity} {servicePlan?.time_unit}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}