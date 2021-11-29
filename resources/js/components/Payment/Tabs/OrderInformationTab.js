import React from 'react';

export default function OrderInformationTab({ order }) {
    let content = order ? (
        <div></div>
    ) : null

    let tab = (
        <div className="tab-pane px-7" id="order_information" role="tabpanel">
            {content}
        </div>
    )

    return tab
}