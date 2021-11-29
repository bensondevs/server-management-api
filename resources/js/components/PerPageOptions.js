import React from 'react';

export const PerPageOptions = ({onValueChange, defaultPerPage}) => {
    return (
        <select onChange={onValueChange} value={defaultPerPage} className="form-control">
            <option value="1">1</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="500">500</option>
        </select>
    )
}