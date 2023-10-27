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
    const [services, setServices] = useState([]);
    const [searchInput, setSearchInput] = useState("");

    useEffect( () => {
        setServices(props.services.data)
        setLoader(false);
    },[props]);

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

    const filterData = services.filter((row) => {
        const { name, price } = row;
        // let searchValue = event.target.value;
        const searchValue = searchInput.toLowerCase();

        return (name.toLowerCase().includes(searchValue) || row.includes(searchValue));
    });

    const handleSearchInputChange = (event) => {
        setSearchInput(event.target.value);
    };

    const handleEdit = async (id, columnName, value) => {
        setLoader(true);
        console.log(id,columnName,value);
        const updatedData = services.map(item => {
            if (item.id === id) {
                return { ...item, [columnName]: value };
            }

            return item;
        });
        setServices(updatedData);
       await axios.post(route('updateServicePrice'), {
            id: id,
            price: value,
        }).then(function (response) {
            console.log(response);
            if(response.data.status == 200){
                setLoader(false);
                toast.success(response.data.message);
            }else{
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
        if (new_price.includes('-')) {
            return false
        }
        item.price  = new_price;
    }
    const handleKeyPress = (event) => {
        // Prevent the input of the minus sign (-)
        if (event.key === '-' || event.keyCode === 45) {
            event.preventDefault();
        }
    };
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Services offered</h2>}
        >
            <Head title="Services"/>
            {
                loader ?  <LoadingCircle loading={loader}/> : (
                    <>
                        <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                            <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Services offered</h3>
                        </div>
                        <div className="at-ideasarea w-full">
                            <div className="at-themetablearea at-ideastablearea w-full">
                                <table className="at-themetable">
                                    <thead>
                                    <tr>
                                        <th className="font-product_sansbold">Name</th>
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
                                            <td data-title="price" className="text-left ">
                                                {/*<span>{item.price}</span>*/}
                                                <div className="pr-20">
                                                    <input
                                                        type="number"
                                                        min="0"
                                                        defaultValue={item.price}
                                                        onChange={e => handleChange(item, e.target.value)}                                                        placeholder="Price"
                                                        className="!border !border-[#C0BCBC] !h-[50px] rounded-md px-5 text-right number-input"
                                                        onKeyPress={handleKeyPress}
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
                                    )) :  <tr key={1}>
                                        <td colSpan={2}>
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
