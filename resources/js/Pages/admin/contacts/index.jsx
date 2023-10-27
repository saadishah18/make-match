import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import {toast} from "react-toastify";
import ReactPaginate from "react-paginate";
import {Inertia} from "@inertiajs/inertia";


export default function ContactEmails(props) {
    const {flash} = usePage().props;
    const [loader, setLoader] = useState(true);
    const [usersList, setUsersList] = useState([]);
    const [searchInput, setSearchInput] = useState("");
    const {users}  = usePage().props;
    const { current_page, last_page, path } = users;


    useEffect( () => {
        setUsersList(props.users.data)
        setLoader(false);
    },[props]);

    const handlePageChange = (selected) => {
        const newPage = selected.selected + 1;
        Inertia.visit(`${path}?page=${newPage}`);
    };

    useEffect(() => {
        if (flash.message) {
            toast.error(flash.message);
            flash.message = '';
        }
        if (flash.success) {
            toast.success(flash.success);
            flash.success = '';
        }
        if (flash.error) {
            toast.error(flash.error);
            flash.error = '';
        }
    }, [flash])

    const handleSearchInputChange = (event) => {
        setSearchInput(event.target.value);
    };

    const filterData = usersList.filter((row) => {

        const { firstname, lastname, email, message } = row;
        // let searchValue = event.target.value;
        const searchValue = searchInput.toLowerCase();

        return (firstname.toLowerCase().includes(searchValue) ||
            lastname.toLowerCase().includes(searchValue) ||
            email.toLowerCase().includes(searchValue)
            // imam_nikahs_count.toLowerCase.includes(searchValue)
        );
    });

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Contact Form Users</h2>}
        >
            <Head title="Contacts Listing"/>
            {
                loader ? 'Loading' : (
                    <>
                        <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                            <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Contact Form Users</h3>
                            <form className="at-searchform min-w-[370px]" onSubmit={event => event.preventDefault()}>
                                <fieldset className="">
                                    <div className="form-group relative">
                                        <input
                                            className="h-[50px] rounded-lg border pr-[40px] border-bordercolor text-black placeholder:text-gray1 focus:border-black focus:ring-0"
                                            type="text"
                                            name="search"
                                            placeholder="Search" onChange={handleSearchInputChange}
                                        />
                                        <svg className="absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                                            width="20" height="20" viewBox="0 0 21 21"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fillRule="evenodd" clipRule="evenodd"
                                                d="M9 1.75C4.99594 1.75 1.75 4.99594 1.75 9C1.75 13.0041 4.99594 16.25 9 16.25C13.0041 16.25 16.25 13.0041 16.25 9C16.25 4.99594 13.0041 1.75 9 1.75ZM0.25 9C0.25 4.16751 4.16751 0.25 9 0.25C13.8325 0.25 17.75 4.16751 17.75 9C17.75 11.1462 16.9773 13.112 15.6949 14.6342L20.5303 19.4697C20.8232 19.7626 20.8232 20.2374 20.5303 20.5303C20.2374 20.8232 19.7626 20.8232 19.4697 20.5303L14.6342 15.6949C13.112 16.9773 11.1462 17.75 9 17.75C4.16751 17.75 0.25 13.8325 0.25 9Z"
                                                fill="#C0BCBC"
                                            />
                                        </svg>
                                    </div>
                                </fieldset>
                            </form>
                            {/*<PrimaryButton*/}
                            {/*    type="button"*/}
                            {/*    onclick={openModalOne}*/}
                            {/*>*/}
                            {/*    Add Imam*/}
                            {/*</PrimaryButton>*/}
                        </div>
                        <div className="at-ideasarea w-full">
                            <div className="at-themetablearea at-ideastablearea w-full">
                                <table className="at-themetable">
                                    <thead>
                                    <tr>
                                        <th className="font-product_sansbold">First Name</th>
                                        <th className="font-product_sansbold">Last Name</th>
                                        <th className="font-product_sansbold">Email</th>
                                        <th className="font-product_sansbold">Message</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    {filterData.length ? filterData.map((item, i) => (
                                        <tr key={i}>
                                            <td data-title="name">
                                                <div className="at-themeemployeinfo at-bdleftborder">
                                                    <div className="at-usernameemail">
                                                        <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.firstname}</h3>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-title="email" className="text-left">
                                                <span>{item.lastname}</span>
                                            </td>
                                            <td data-title="email" className="text-left">
                                                <span> <a href={`mailto:${item.email}`}>
                                                    <img
                                                       className="w-[40px] p-0 inline-block"
                                                       width="50px"
                                                        src="/assets/images/svg/email-envelope.svg"
                                                        alt="Envelope"
                                                    />
                                                    &nbsp;&nbsp;
                                                    {item.email}
                                                </a>
                                                </span>
                                            </td>
                                            <td data-title="Department" className="text-left">
                                                <span>{item.message}</span>
                                            </td>


                                        </tr>
                                    )) :  <tr>
                                        <td colSpan={5}>
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
                                <ReactPaginate
                                    breakLabel="..."
                                    nextLabel=">"
                                    previousLabel="<"
                                    renderOnZeroPageCount={null}
                                    pageCount={last_page}
                                    pageRangeDisplayed={3}
                                    marginPagesDisplayed={1}
                                    onPageChange={handlePageChange}
                                    forcePage={current_page - 1}
                                    // containerClassName="pagination"
                                    activeClassName="active"
                                    className="at-pagenation"
                                />
                            </div>
                        </div>
                    </>
                )
            }

        </AuthenticatedLayout>
    );
}
