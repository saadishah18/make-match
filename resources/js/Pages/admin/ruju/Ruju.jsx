import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from '@inertiajs/inertia-react';
import {RujuData} from "../../../../Data/RujuData";
import AssignImamModal from "@/Components/AssignImamModal";
import ReactPaginate from "react-paginate";

export default function Ruju(props) {
    console.log({props});
    let [isOpen, setIsOpen] = useState(false)
    const [loader, setLoader] = useState(true);
    const [rujuList, setRujuList] = useState([]);
    const [defaultList, setDefaultList] = useState([]);
    const [searchInput, setSearchInput] = useState("");

    useEffect(() => {
        setRujuList(props.rujus.data)
        setDefaultList(props.rujus.data)
        setLoader(false);
    }, [props]);

    function closeModal() {
        setIsOpen(false)
    }

    function openModal() {
        setIsOpen(true)
    }

    const handleSearchInputChange = (event) => {
        setSearchInput(event.target.value);
    };
    const filterData = rujuList.filter((row) => {
        const { requester, groom, first_ruju_applied_date, ruju_counter } = row;
        // let searchValue = event.target.value;
        const searchValue = searchInput.toLowerCase();

        return (requester.toLowerCase().includes(searchValue) ||
            groom.toLowerCase().includes(searchValue) ||
            first_ruju_applied_date.toLowerCase().includes(searchValue) ||
            ruju_counter.toLowerCase().includes(searchValue)
        );
    });


    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Ruju</h2>}
        >
            <Head title="Ruju Management"/>
            <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Ruju Management</h3>
                <form className="at-searchform min-w-[370px]" onSubmit={e => e.preventDefault()}>
                    <fieldset className="">
                        <div className="form-group relative">
                            <input
                                className="h-[50px] rounded-lg border pr-[40px] border-bordercolor text-black placeholder:text-gray1 focus:border-black focus:ring-0"
                                type="text"
                                name="search"
                                placeholder="Search" onChange={handleSearchInputChange}
                            />
                            <svg
                                className="absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                                width="20"
                                height="20"
                                viewBox="0 0 21 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fillRule="evenodd"
                                    clipRule="evenodd"
                                    d="M9 1.75C4.99594 1.75 1.75 4.99594 1.75 9C1.75 13.0041 4.99594 16.25 9 16.25C13.0041 16.25 16.25 13.0041 16.25 9C16.25 4.99594 13.0041 1.75 9 1.75ZM0.25 9C0.25 4.16751 4.16751 0.25 9 0.25C13.8325 0.25 17.75 4.16751 17.75 9C17.75 11.1462 16.9773 13.112 15.6949 14.6342L20.5303 19.4697C20.8232 19.7626 20.8232 20.2374 20.5303 20.5303C20.2374 20.8232 19.7626 20.8232 19.4697 20.5303L14.6342 15.6949C13.112 16.9773 11.1462 17.75 9 17.75C4.16751 17.75 0.25 13.8325 0.25 9Z"
                                    fill="#C0BCBC"
                                />
                            </svg>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div className="at-ideasarea w-full">
                <div className="at-themetablearea at-ideastablearea w-full">
                    <table className="at-themetable">
                        <thead>
                        <tr>
                            <th className="font-product_sansbold">Requester</th>
                            <th className="font-product_sansbold">Ruju From</th>
                            <th className="font-product_sansbold">Date</th>
                            {/*<th className="font-product_sansbold">Time</th>*/}
                            <th className="font-product_sansbold">Counter</th>
                            {/*<th className="!text-center font-product_sansbold">Action</th>*/}
                        </tr>
                        </thead>
                        <tbody>

                        {filterData.length ? filterData.map((item, i) => (
                            <tr key={i}>
                                <td data-title="Employee">
                                    <div className="at-themeemployeinfo at-bdleftborder">
                                        <div className="at-usernameemail">
                                            <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.requester}</h3>
                                        </div>
                                    </div>
                                </td>
                                <td data-title="Designation" className="text-left">
                                    <span>{item.groom}</span>
                                </td>
                                <td data-title="Designation" className="text-left">
                                    <span>{item.ruju_date}</span>
                                </td>
                                {/*<td data-title="Department" className="text-left">
                                    <span>{item.time}</span>
                                </td>*/}
                                <td data-title="Department" className="text-left">
                                    <span>{item.ruju_counter}</span>
                                </td>
                                {/*<td data-title="Action" className="px-2">*/}
                                {/*    <button className="bg-[#00cc6f] text-white text-base rounded-lg font-product_sansregular px-[25px] min-h-[40px] flex items-center justify-center mx-auto" type="button" onClick={openModal}>Assign Imam</button>*/}
                                {/*</td>*/}
                            </tr>
                        )) : <tr>
                            <td colSpan={4}>
                                <div className="flex justify-center items-center min-h-[650px]">
                                    <img
                                        src="/assets/images/nodata-found.png"
                                        alt="no data found"
                                    />
                                </div>
                            </td>
                        </tr>
                        }
                        </tbody>
                    </table>
                   {/* <ReactPaginate
                        breakLabel="..."
                        nextLabel=">"
                        pageRangeDisplayed={5}
                        previousLabel="<"
                        renderOnZeroPageCount={null}
                        className="at-pagenation"
                    />*/}
                </div>
            </div>
            <AssignImamModal isOpen={isOpen} closeModal={closeModal} imamslist={[]}/>
        </AuthenticatedLayout>
    );
}
