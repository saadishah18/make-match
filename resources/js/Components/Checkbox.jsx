import React from "react";

export default function Checkbox({ name, value, handleChange, className }) {
    return (
        <input
            type="checkbox"
            name={name}
            value={value}
            className={`${className} rounded border-gray-300 text-themecolor shadow-sm focus:border-themecolor focus:ring-0 focus:shadow-none`}
            onChange={(e) => handleChange(e)}
        />
    );
}
