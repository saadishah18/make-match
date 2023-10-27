import { Dialog, Transition } from "@headlessui/react";
import { Fragment, useEffect, useState } from "react";
import Select from "react-select";
import LoadingCircle from "@/Components/LoadingCircle";
import { toast } from "react-toastify";
import { Inertia } from "@inertiajs/inertia";

export default function AssignImamModal(props) {
    // console.log({props});
    const [imamOptions, setImamOptions] = useState([]);
    const [imam_id, setImamID] = useState("");
    const [loader, setLoader] = useState(true);

    useEffect(() => {
        let options = [];
        for (let imam of props.imamslist) {
            options.push({
                value: imam.id,
                label: imam.first_name + " " + imam.last_name,
                id: imam.id,
            });
        }
        setImamOptions(options);
    }, [props]);

    function funcHandler() {
        props.isOpen(true);
        props.closeModal(false);
    }

    const handleAssignImam = () => {
        // props.closeModal(false)
        console.log(props.type);
        let url = "";
        if (props.type == "nikah") {
            url = route("assign-imam");
        }else if (props.type == "change-imam") {
            url = route("assign-imam");
        } else {
            url = route("assignImamToKhulu");
        }
        setLoader(true);
        axios.post(url, {
            nikah_id: props.nikah_id,
            imam_id: imam_id,
        }).then(function (response) {
                if (response.data.status == 200) {
                    setLoader(false);
                    props.closeModal(false);
                    // alert(response.data.message);
                    toast.success(response.data.message);
                    Inertia.reload();
                } else {
                    props.closeModal(false);
                    setLoader(false);
                    toast.error(response.data.message);
                }
            })
            .catch(function (error) {
                console.log({ error });
                alert("error");
                let { response } = error;
                props.closeModal(false);
                setLoader(false);
                toast.error(response.data.message);
            });
    };

    const customStyles = {
        option: (defaultStyles, state) => ({
            ...defaultStyles,
            fontWeight: state.isSelected ? "bold" : "bold",
            color: state.isSelected ? "#fff" : "#000",
            backgroundColor: state.isSelected ? "#BE2D87" : "#fff",
        }),
    };

    return (
        <>
            <Transition appear show={props.isOpen} as={Fragment}>
                <Dialog
                    as="div"
                    className="relative z-10"
                    onClose={props.closeModal}
                >
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-300"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <div className="fixed inset-0 bg-black bg-opacity-25" />
                    </Transition.Child>

                    <div className="fixed inset-0 overflow-y-auto">
                        <div className="flex min-h-full items-center justify-center p-4 text-center">
                            <Transition.Child
                                as={Fragment}
                                enter="ease-out duration-300"
                                enterFrom="opacity-0 scale-95"
                                enterTo="opacity-100 scale-100"
                                leave="ease-in duration-200"
                                leaveFrom="opacity-100 scale-100"
                                leaveTo="opacity-0 scale-95"
                            >
                                <Dialog.Panel className="w-full max-w-[505px] transform rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <Dialog.Title
                                        as="h3"
                                        className="text-xl text-center font-product_sansregular mb-6 text-black"
                                    >
                                        Assign Imam
                                    </Dialog.Title>
                                    <div className="w-full">
                                        <form className="">
                                            <fieldset>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Select Imam
                                                    </label>
                                                    <Select
                                                        placeholder="Select Imam"
                                                        options={imamOptions}
                                                        className="at-chartselect sm:mb-0 mb-5"
                                                        styles={customStyles}
                                                        // onChange={async (e) => {
                                                        //     await setYear(e.value);
                                                        //     handleChartFilter(month, e.value)
                                                        // }}
                                                        name="year_filter"
                                                        onChange={(e) => {setImamID(e.value);}}
                                                        noOptionsMessage={() => "No imam is available right now"}
                                                    />
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div className="mt-4 flex flex-col items-center justify-center">
                                        <button
                                            type="button"
                                            className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                            // onClick={funcHandler}
                                            // onClick={props.closeModal}
                                            onClick={handleAssignImam}
                                        >
                                            Assign
                                        </button>
                                        {/*<button
                                            type="button"
                                            className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto mt-2"
                                            // onClick={funcHandler}
                                            onClick={props.closeModal}
                                        >
                                            Change Imam
                                        </button>*/}
                                    </div>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    );
}
