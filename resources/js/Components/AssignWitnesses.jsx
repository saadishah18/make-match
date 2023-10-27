import { Dialog, Transition } from '@headlessui/react'
import {Fragment, useEffect, useState} from 'react'
import Select from 'react-select'
import {toast} from "react-toastify";


export default function AssignWitnessesModal(props) {
    const [witnessOptions, setWitnessesOptions] = useState([]);
    const [witnessOne, setWitnessOne] = useState('');
    const [witnessTwo, setWitnessTwo] = useState('');
    const [loader, setLoader] = useState(false);

    useEffect( () => {
        let options = [];
        for (let user of props.witnessArray) {
            options.push({value: user.id, label: user.first_name+' '+user.last_name, id:user.id});

        }
        setWitnessesOptions(options);
    },[props])

    function funcHandler() {
        props.isOpen(true)
        props.closeModal(false)
    }

    const handleAssignWitness = () =>{
        if(witnessOne == witnessTwo){
            toast.error('Both witness cannot be same')
            return false;
        }
        if(witnessOne == '' || witnessTwo == ''){
            toast.error('Select two witnesses')
            return false;
        }

        setLoader(true);
        let witness_ids = []
        witness_ids.push(witnessOne);
        witness_ids.push(witnessTwo);
        axios.post(route('assign-witness'), {
                nikah_id: props.nikah_id,
            witness_ids: witness_ids,
        }).then(function (response) {
            if(response.data.status == 200){
                setLoader(false);
                props.closeModal(false);
                toast.success(response.data.message)
            }else{
                props.closeModal(false);
                setLoader(false);
                // alert('wrong');
                toast.error('Something went wrong');
            }

        }).catch(function (error) {
            props.closeModal(false);
            console.log(error);
            setLoader(false);
            // alert('error');
            toast.error(error);


        });
    }
    return (
        <>
            <Transition appear show={props.isOpen} as={Fragment}>
                <Dialog as="div" className="relative z-10" onClose={props.closeModal}>
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
                                        Assign Witnesses
                                    </Dialog.Title>
                                    <div className="w-full">
                                        <form className="">
                                            <fieldset>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Select Witnesses 1
                                                    </label>
                                                    <Select
                                                        placeholder="Select Witnesses 1"
                                                        options={witnessOptions}
                                                        className="at-chartselect sm:mb-0 mb-5"
                                                        // onChange={async (e) => { setWitnessIDs(witness_ids.push(e.value))}}
                                                        onChange={(e) =>  setWitnessOne(e.value)}
                                                        name="witness_one"
                                                    />
                                                </div>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Select Witnesses 2
                                                    </label>
                                                    <Select
                                                        placeholder="Select Witnesses 2"
                                                        options={witnessOptions}
                                                        className="at-chartselect sm:mb-0 mb-5"
                                                        onChange={(e) => setWitnessTwo(e.value)}
                                                        name="witness_two"
                                                    />
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div className="mt-4 flex items-center justify-center">
                                        <button
                                            type="button"
                                            className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                            onClick={handleAssignWitness}
                                            // onClick={props.closeModal}
                                        >
                                            Assign
                                        </button>
                                    </div>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    )
}
