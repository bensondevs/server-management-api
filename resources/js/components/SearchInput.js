import React, { useState } from 'react';

export const SearchInput = ({handleSearch, timeout = 800}) => {
    const [typingTimeout, setTypingTimeout] = useState(0);

    const onSearchTyping = (event) => {
        let keyword = event.target.value;

        if (typingTimeout) {
            clearTimeout(typingTimeout);
            setTypingTimeout(0);
        }
        
        setTypingTimeout(setTimeout(() => {
            handleSearch(keyword);
        }, timeout));
    }

    return (
        <div className="input-icon">
            <input 
                onChange={onSearchTyping}
                type="text" 
                className="form-control" 
                placeholder="Search..." />
            <span><i className="flaticon2-search-1 text-muted"></i></span>
        </div>
    );
}