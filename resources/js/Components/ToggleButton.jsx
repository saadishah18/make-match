import {Switch} from "@headlessui/react";
import React, {useEffect, useState} from "react";
import axios from "axios";
import {toast} from "react-toastify";

const ToggleButton = ({ status,imam_id }) => {
    //props => { status, name }
    const [enabled, setEnabled] = useState(status);
    const [isLoading, setIsLoading] = useState(false);

    const handleSwitch = (event) => {
        setIsLoading(true);
        axios.post(route('changeImamStatus'), {
            imam_id: imam_id,
            status: event
        }).then(function (response) {
            let {data} = response;
            toast.success(data.message);
            setEnabled(!enabled);
            setIsLoading(false);
        }).catch(function (error) {
            console.log(error);
            toast.error('Something went wrong')
        });
    }
  /*  useEffect( () => {

    },[])*/
    return <>
        {
            isLoading ? 'Loading..' : (
                <Switch
                    checked={enabled}
                    onChange={handleSwitch}
                    className={`${enabled ? 'bg-themecolor' : 'bg-[#75797f]'} relative inline-flex h-[29px] w-[65px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2  focus-visible:ring-white focus-visible:ring-opacity-75`}>
             <span className="at-themetoolip">
                 {
                     enabled ? 'Active' : 'In-active'
                 }
             </span>
                    <span
                        aria-hidden="true"
                        className={`${enabled ? 'translate-x-9' : 'translate-x-0'}
                                                pointer-events-none inline-block h-[25px] w-[25px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out`}
                    />
                </Switch>
            )
        }

    </>
}

export default ToggleButton;
