import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import {toast} from "react-toastify";
import ReactPaginate from "react-paginate";
import {Inertia} from "@inertiajs/inertia";
import LoadingCircle from "@/Components/LoadingCircle";
import PrimaryButton from "@/Components/PrimaryButton";


export default function ContactEmails(props) {
    const {flash} = usePage().props;
    const [loader, setLoader] = useState(true);
    const [types, setTypes] = useState([]);
    const [searchInput, setSearchInput] = useState("");


    useEffect(() => {
        setTypes(props.types.data)
        setLoader(false);
    }, [props]);

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

    const filterData = types.filter((row) => {
        const {name, description} = row;
        const searchValue = searchInput.toLowerCase();
        return (name.toLowerCase().includes(searchValue) || description.toLowerCase().includes(searchValue));
    });

    const handleEdit = async (id, columnName, value) => {
        setLoader(true);
        const updatedData = types.map(item => {
            if (item.id === id) {
                return {...item, [columnName]: value};
            }

            return item;
        });
        setTypes(updatedData);
        await axios.post(route('updateNikahType'), {
            id: id,
            price: value,
        }).then(function (response) {
            console.log(response);
            if (response.data.status == 200) {
                setLoader(false);
                toast.success(response.data.message);
            } else {
                setLoader(false);
                toast.error('Some thing went wrong');
            }

        }).catch(function (error) {
            console.log(error);
            setLoader(false);
            toast.error(error);

        });
    };

    const handleChange = (item,new_price) => {
        item.price  = new_price;
    }


    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Nikah Types</h2>}
        >
            <Head title="Nikah types Management"/>
            {
                loader ? <LoadingCircle loading={loader}/> : (
                    <>
                        <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                            <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Nikah types</h3>
                        </div>
                        <div className="at-ideasarea w-full">
                            <div className="at-themetablearea at-ideastablearea w-full">
                                <table className="at-themetable">
                                    <thead>
                                    <tr>
                                        <th className="font-product_sansbold text-left">Name</th>
                                        <th className="font-product_sansbold text-left">Description</th>
                                        <th className="font-product_sansbold !text-right !pr-20">Price</th>
                                        <th className="font-product_sansbold !text-right !pr-20">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    {filterData.length ? filterData.map((item, i) => (
                                        <tr key={i}>
                                            <td data-title="name">
                                                <div className="at-themeemployeinfo at-bdleftborder">
                                                    <div className="at-usernameemail">
                                                        <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.name}</h3>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-title="description">
                                                <div className="at-usernameemail">
                                                    <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.description}</h3>
                                                </div>
                                            </td>
                                            <td data-title="price" className="text-left ">
                                                <div className="pr-20">
                                                    <input
                                                        type="number"
                                                        min="0"
                                                        defaultValue={item.price}
                                                        onChange={e => handleChange(item, e.target.value)}
                                                        placeholder="Price"
                                                        className="!border !border-[#C0BCBC] !h-[50px] rounded-md px-5 text-right number-input"
                                                    />
                                                </div>

                                            </td>
                                            <td>
                                                <PrimaryButton
                                                    type="button"
                                                    onclick={e => handleEdit(item.id, 'price', item.price)}
                                                >Update </PrimaryButton>
                                            </td>
                                        </tr>
                                    )) : <tr key={1}>
                                        <td colSpan={3}>
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
                            </div>
                        </div>
                    </>
                )
            }

        </AuthenticatedLayout>
    );
}
