import React, { useState, useRef, useCallback } from "react";
import { Head, Link } from "@inertiajs/inertia-react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { HiPaperAirplane } from "react-icons/hi2";
import { GrAttachment } from "react-icons/gr";
import {AiOutlineDownload,
    AiFillFile, AiFillCloseCircle,} from "react-icons/ai";
import { useEffect } from "react";
import { usePage } from "@inertiajs/inertia-react";
import { toast } from "react-toastify";
import Pusher from "pusher-js";
import NewChat from "@/Components/NewChat";
import LoadingCircle from "@/Components/LoadingCircle";
import moment from "moment";
import {debounce} from "lodash";

export default function Chat(props) {
    const [toggle, setToggle] = useState(false);
    const {user} = usePage().props.auth;
    const [messages, setMessages] = useState([]);
    const [chatList, setChatList] = useState([]);
    const [activeChatID, setActiveChatID] = useState("");
    const [chatName, setChatName] = useState(null);
    const [profileImage, setProfileImage] = useState("");
    const [adminReply, setAdminReply] = useState("");
    const [receiverID, setReceiverID] = useState(user.id);
    const [defaultChat, setDefaultChat] = useState([]);
    const messagesEndRef = useRef(null);
    const [isOpenOne, setIsOpenOne] = useState(false);
    const [loader, setLoader] = useState(false);
    const [usersList, setUsersList] = useState([]);
    const [bool, setBool] = useState(0);
    const [audio] = useState(new Audio('/assets/audios/notification.wav'));
    const [isFetching, setIsFetching] = useState(false);
    const [previewImage, setPreviewImage] = useState(null);


    useEffect(() => {
        // Initialize Pusher client
        const pusher = new Pusher("de37fb7c0c4415b27884", {
            cluster: "ap2",
            authEndpoint: '/pusher/auth', // The authentication endpoint you defined

        });
        // Subscribe to channel
        pusher.unsubscribe("private-laravel." + user.id);
        // const channel = pusher.subscribe("private-laravel." + user.id);
        const channel = pusher.subscribe("private-laravel." + user.id);
        let chat_array = [];
        let chats = props.chats;
        chats.forEach(function (item) {
            const words = item.message != null ? item.message.split(" ") : "";
            const firstFiveWords = words ? words.slice(0, 6).join(" ") : "";
            chat_array.push({
                id: item.chat_id,
                name: item.chat_name,
                sentTime: item.time,
                profile_image: item.profile_image,
                message: words.length <= 3 ? firstFiveWords : firstFiveWords + "...",
                // message: item.message,
                is_highlight: item.is_highlight,
            });
        });
        setChatList(chat_array);
        setDefaultChat(chat_array);

        channel.bind("support.message", (data) => {
            console.log("Received data:", data); // Debug: Check the received data
            pusherCallback(data, chat_array);
        });
    }, [props.chats]);

    const getChatDetail = async (chatID, chatName) => {
        setPreviewImage(null);
       await axios.get(route("chat-detail", chatID)).then((response) => {
            let {data} = response;
            if (data.hasOwnProperty('data') == false) {
                setMessages([]);
                return false;
            }

            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?chat=" + chatID;
            window.history.pushState({path: newurl}, "", newurl);
            setBool(1);
            let message_array = [];
            setActiveChatID(chatID);
            setChatName(chatName);
            let tempChat = chatList.find((chat) => chat.id == chatID);
            typeof tempChat != 'undefined'  ? setProfileImage(tempChat.profile_image) : setProfileImage('/assets/images/users/avatar1.jpg');

            if (data.data && typeof data.data[0] != "undefined" && data.hasOwnProperty('data')) {
                if (user.id == data.data[0].sender_id) {
                    setReceiverID(data.data[0].receiver_id);
                } else {
                    setReceiverID(data.data[0].sender_id);
                }
                data.data.forEach(function (item) {
                    message_array.push({
                        id: item.id,
                        sentTime: item.time,
                        date: item.date,
                        message: item.message,
                        senderID: item.sender_id,
                        // receiverID: item.receiver_id,
                        receiverID: receiverID,
                        file_url: item.file_url,
                        file_type: item.file_type,
                        file_name: item.file_name,
                        user_image: item.user_image,
                    });
                });
                if (activeChatID) {
                    let chatIndex = chatList.findIndex((chat) => chat.id == activeChatID);
                    chatList[chatIndex] = {...chatList[chatIndex], is_highlight: false};
                }
                setMessages(message_array);

            }
        }).catch(function (error) {
            console.log('saad',error);
            toast.error(error.message);
        });
    };

    const handleUserChange = useCallback(async (e) => {
        let new_chat = {};
        setReceiverID(e.value);
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?chat=" + e.id;
        window.history.pushState({path: newurl}, "", newurl);
        new_chat = {
            id: e.id,
            name: e.label.split("(")[0],
            profile_image: e.profile_image,
            sentTime: moment().fromNow(),
            message: "",
            is_highlight: true,
        };
        await setChatList([new_chat, ...chatList]);
        // await setDefaultChat(chatList);
        await setActiveChatID(e.id);
        await setChatName(e.label.split("(")[0]);
        setBool(1);
        closeModalOne();
        await getChatDetail(e.id, e.label);
    }, [chatList, receiverID]);


    const [file, setFile] = useState(null);

    function pusherCallback(data, chat_array) {
        let search = window.location.search;
        let params = new URLSearchParams(search);
        let chat_id = params.get("chat");
        let newMessage = data.message;
        console.log({newMessage})
        console.log({user})
        if (chat_id == newMessage.chat_id) {
            setMessages((messages) => [...messages, newMessage]);
            // setMessages((messages) => [...messages, { type: 'file', url: data.url }]);
        } else {
            let chatIndex = chat_array.findIndex((chat) => chat.id == newMessage.chat_id);
            if (chatIndex == -1) {
                getLatestChats(newMessage.chat_id,chat_array);
            } else {
                chat_array[chatIndex] = {...chat_array[chatIndex], is_highlight: true,};

            const words = newMessage.message != null ? newMessage.message.split(" ") : "";
                const firstFiveWords = words ? words.slice(0, 6).join(" ") : "";

            chat_array[chatIndex] = {...chat_array[chatIndex], message: firstFiveWords};
                setChatList([...chat_array]);
            }
        }
        console.log('sender', newMessage.sender_id, 'user',user.id)
        if (newMessage.sender_id != user.id) {
            audio.play();
        }

    }

    const getLatestChats = async (chat_id = null, chat_array = null) => {
        await axios.post(route("get-latest-chat"), {
            chat_id: chat_id,
        }).then((response) => {
            let {data} = response.data;
            let latest_chat = {};
            data.forEach(function (item) {
                const words = item.message != null ? item.message.split(" ") : "";
                const firstFiveWords = words ? words.slice(0, 6).join(" ") : "";
                latest_chat = {
                    id: item.chat_id,
                    name: item.chat_name,
                    sentTime: item.time,
                    profile_image: item.profile_image,
                    message: words.length <= 3 ? firstFiveWords : firstFiveWords + "...",
                    is_highlight: true,
                };
            })
            chat_array.unshift(latest_chat);
            setChatList([...chat_array]);
        });
    }

    const sendMessageToUser = async () => {

        if (file == null && adminReply == '') {
            toast.error("Please attach file or send message");
            return false;
        }
        const formData = new FormData();
        formData.append("file", file);
        formData.append("sender_id", user.id);
        formData.append("receiver_id", receiverID);
        formData.append("message", adminReply);
        formData.append("chat_id", activeChatID);
        await axios.post(route("post-reply"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                "X-Requested-With": "XMLHttpRequest",
            },
        }).then((response) => {
            let {data} = response.data;
            let message_array = {
                id: data.id,
                sentTime: data.time,
                date: data.date,
                message: data.message,
                senderID: data.sender_id,
                file_url: data.file_url,
                file_type: data.file_type,
                file_name: data.file_name,
                user_image: data.user_image,
            };

            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?chat=" + data.chat_id;
            window.history.pushState({path: newurl}, "", newurl);
            let chatIndex = chatList.findIndex((chat) => chat.id == activeChatID);
            chatList[chatIndex] = {...chatList[chatIndex], message: data.message != null ? data.message : 'File '+data.file_name};
            chatList[chatIndex] = {...chatList[chatIndex], sentTime: data.time};

            setActiveChatID(data.chat_id);
            // alert(activeChatID);
            setMessages((messages) => [...messages, message_array]);
            setAdminReply("");
            setFile(null);
            setPreviewImage(null);
        });
    };

    const scrollToBottom = () => {
        if (messages.length) {
            messagesEndRef?.current?.scrollIntoView({behavior: "smooth"});
        }
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    const filterChats = (e) => {
        e.preventDefault();
        let value = e.target.value;
        const search = value.toLowerCase();
        if (search.length > 3) {
            let filterdChatsResp = chatList.find((chat) => chat.name.toLowerCase().match(search));
            if (_.has(filterdChatsResp, "id")) {
                setChatList([filterdChatsResp]);
            } else {
                setChatList([]);
            }
        } else if (search.length < 3) {
            setChatList(defaultChat);
        }
    };

    function closeModalOne() {
        setIsOpenOne(false);
    }

    function openModalOne() {
        axios.post(route("get-users-for-chat"), {
            admin_id: user.id,
        }).then((response) => {
                let chats =  chatList.filter((item) => item.message != '')
                setChatList(chats);
                let {data} = response.data;
                let chat_users = [];
                data.forEach(function (item) {
                    chat_users.push({
                        id: item.chat_id,
                        value: item.value,
                        label: item.name,
                        profile_image: item.profile_image,
                    });
                });
                setUsersList(chat_users);
                setIsOpenOne(true);
            });
    }

    const handleFileUpload = (event) => {
        setPreviewImage(null);
        let image = event.target.files[0];
        if (image.size / 1024 / 1024 > 5) {
            toast.error("Max upload size is 5 Mb");
            return;
        }
        setFile(image);
        const imageUrl = URL.createObjectURL(image);
        setPreviewImage(imageUrl);
    };

    const removeAttachment = (e) => {
        setPreviewImage(null);
    };

    const handleDownload = (url) => {
        const link = document.createElement("a");
        link.href = url;
        link.download = "attachment";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    const handleKeyDown = (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            // Check if message is not empty and not already fetching
            if (!isFetching) {
                debouncedAPICall();
            }
        }
    };

    // Debounce the API call function to delay execution
    const debouncedAPICall = debounce(() => {
        // Call your API here
        // Set the fetching status accordingly
        setIsFetching(true);

        // Simulate API call for demonstration purpose
        setTimeout(() => {
            setIsFetching(false);
            // console.log('API called!');
            sendMessageToUser()
        }, 1500);
    }, 1000);


    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Chat
                </h2>
            }
        >
            <Head title="Support chat"/>
            {loader ? (
                <LoadingCircle loading={loader}/>
            ) : (
                <>
                    <div className="mb-6 block md:flex items-center justify-between">
                        <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">
                            Chat
                        </h3>
                        <button
                            type="button"
                            onClick={openModalOne}
                            className="inline-flex items-center justify-center  bglinear-gradient py-3 px-6 xl:py-4 xl:px-12 border border-transparent rounded-[10px] font-product_sansregular font-bold text-lg text-white capitalize tracking-widest active:bg-black transition ease-in-out duration-150"
                        >
                            New Chat
                        </button>
                    </div>
                    <div className="rt-content rt-chatroomarea w-full">
                        <div
                            className={
                                toggle
                                    ? "rt-chatroom rt-chatsidebar-expand"
                                    : "rt-chatroom"
                            }
                        >
                            <button
                                type="button"
                                className="rt-chatbtn"
                                onClick={() => {
                                    setToggle(!toggle);
                                }}
                            >
                                <i className="icon-down-arrow"></i>
                            </button>
                            <div className="rt-chatsidebar">
                                <div className="rt-head">
                                    <form
                                        className="at-searchform"
                                        onSubmit={(e) => e.preventDefault()}
                                    >
                                        <fieldset className="">
                                            <div className="form-group relative">
                                                <input
                                                    className="h-[46px] rounded-lg border pr-[40px] border-bordercolor text-black placeholder:text-gray1 focus:border-black focus:ring-0"
                                                    type="text"
                                                    name="search"
                                                    placeholder="Search"
                                                    onChange={(e) =>
                                                        filterChats(e)
                                                    }
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
                                <h3 className="mb-3 text-base px-5 pb-3 border-b border-bordercolor">
                                    Chat History
                                </h3>
                                <div className="rt-chatlistbox">
                                    {chatList.length ? (
                                        <>
                                            <div className="rt-chatlist">
                                                {chatList.filter((item) => item.message != '' || item.is_highlight).map((item, i) => (
                                                    <div
                                                        className="rt-nav-item"
                                                        key={item.id}
                                                    >
                                                        <div
                                                            className={activeChatID == item.id ? "rt-themecontent is-Active" : "rt-themecontent"}
                                                            onClick={() => getChatDetail(item?.id, item?.name)}
                                                        >
                                                            <figure className="rt-roundimage">
                                                                {item.profile_image != 'null' ?
                                                                (
                                                                    <img src={item.profile_image} alt="user_profile_image1"/>
                                                                ) : (
                                                                    <img src="/assets/images/users/avatar1.jpg" alt="default_user1"/>
                                                                )}
                                                            </figure>
                                                            <div className={`rt-titlebox ${item?.is_highlight == true ? "highlight-chat" : ""}`}>
                                                                <h5>
                                                                    {item?.name}
                                                                </h5>
                                                                <span className="truncate">
                                                                    {
                                                                        item?.message
                                                                    }
                                                                </span>
                                                            </div>
                                                            <div className="rt-rightbox">
                                                                <span className="rt-time">
                                                                    {
                                                                        item?.sentTime
                                                                    }
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        </>
                                    ) : (
                                        <div className="flex justify-center items-center min-h-[650px]">
                                            <img src="/assets/images/nodata-found.png" className="1-class" alt="no_data_found_1"/>
                                        </div>
                                    )}
                                </div>
                            </div>
                            {/* chat box*/}
                            <div className="rt-tabcontent">
                                <div className="rt-chatcontentarea">
                                    {chatList.length && bool ? (
                                        <>
                                            <div className="rt-head">
                                                <div className="rt-themecontent">
                                                    <figure className="rt-roundimage">
                                                        {profileImage != 'null' ? (
                                                            <img src={profileImage} alt="user image"/>
                                                        ) : (
                                                            <img src="/assets/images/users/avatar1.jpg" alt="user image"/>
                                                        )}
                                                        <span className="rt-statusmark"></span>
                                                    </figure>
                                                    <div className="rt-titlebox">
                                                        <h5>{chatName}</h5>
                                                        {/*<span>@julia.jud32</span>*/}
                                                    </div>
                                                </div>
                                            </div>
                                        </>
                                    ) : (
                                        ""
                                    )}

                                    {chatList.length && bool ? (
                                        <>
                                            <div className="rt-chatcontent">
                                                {messages.length ? messages.map((item, i) => (
                                                            <>
                                                                { user.id != parseInt(item.senderID) ? (
                                                                    <>
                                                                        <div className="rt-messagebox user-message" key={i}>
                                                                            <div className="rt-roundimage">
                                                                                {item.user_image != "null" ? (
                                                                                    <img src={item.user_image}
                                                                                         alt="user image"/>
                                                                                ) : (
                                                                                    <img
                                                                                        src="/assets/images/users/avatar1.jpg"
                                                                                        alt="user image"/>
                                                                                )}
                                                                            </div>
                                                                            <div className="rt-messagetextbox">
                                                                                <div className="rt-title">
                                                                                    <h3>
                                                                                        {item?.name}
                                                                                    </h3>
                                                                                    <span className="rt-timeanddate">
                                                                                          {item?.sentTime}
                                                                                      </span>
                                                                                </div>

                                                                                {item.file_url ? (
                                                                                    <>
                                                                                        <div className="rt-roundimage rt-uploadfile">
                                                                                            {
                                                                                                item.file_type == "png" || item.file_type == "jpg" || item.file_type == "jpeg" ? (
                                                                                                    <>
                                                                                                        <img width="50px" src={item.file_url} alt="png"/>
                                                                                                    </>
                                                                                                ) : item.file_type == "doc" || item.file_type == "docx" ? (
                                                                                                    <>
                                                                                                        <img width="15px" src="/assets/images/file-type-word.svg" alt="doc"/>
                                                                                                    </>
                                                                                                ) : item.file_type == "xlsx" ? (
                                                                                                    <>
                                                                                                        <img width="15px" src="/assets/images/file-type-excel.svg" alt="xlsx"/>
                                                                                                    </>
                                                                                                ) : item.file_type == "pdf" ? (
                                                                                                    <>
                                                                                                        <img width="15px" src="/assets/images/file-type-pdf.svg" alt="pdf"/>
                                                                                                    </>
                                                                                                ) : (
                                                                                                    <>
                                                                                                        <p className="fileName">{item.file_name.length > 5 ? 'attachment.'+item.file_type : item.file_name}</p>
                                                                                                    </>
                                                                                                )
                                                                                            }
                                                                                            <i className="rt-download">
                                                                                                <AiOutlineDownload onClick={(e) => handleDownload(item.file_url)}/>
                                                                                            </i>
                                                                                        </div>
                                                                                    </>
                                                                                ) : (
                                                                                    ""
                                                                                )}
                                                                                <div className="rt-description">
                                                                                    <p>
                                                                                        {item?.message}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </>
                                                                ) : (
                                                                    <>
                                                                        <div className="rt-messagebox rt-right admin-message" key={i + 1}>
                                                                            <div className="rt-messagetextbox">
                                                                                <div className="rt-title">
                                                                                    <h3>
                                                                                        {user?.first_name}
                                                                                    </h3>
                                                                                    <span className="rt-timeanddate">
                                                                                          {item?.sentTime}
                                                                                      </span>
                                                                                </div>
                                                                                <div className="flex gap-3 items-center">
                                                                                    {item.file_url ? (
                                                                                        <>
                                                                                            <div className="rt-roundimage rt-uploadfile">
                                                                                            {
                                                                                                    item.file_type == "png" || item.file_type == "jpg" || item.file_type == "jpeg" ? (
                                                                                                        <>
                                                                                                            <img width="50px" src={item.file_url} alt="png"/>
                                                                                                        </>
                                                                                              ) : item.file_type == "doc" || item.file_type == "docx" ? (
                                                                                                        <>
                                                                                                            <img width="15px" src="/assets/images/file-type-word.svg" alt="doc"/>
                                                                                                        </>
                                                                                              ) : item.file_type == "xlsx" ? (
                                                                                                        <>
                                                                                                            <img width="15px" src="/assets/images/file-type-excel.svg" alt="xlsx"/>
                                                                                                        </>
                                                                                              ) : item.file_type == "pdf" ? (
                                                                                                        <>
                                                                                                            <img width="15px" src="/assets/images/file-type-pdf.svg" alt="pdf"/>
                                                                                                        </>
                                                                                                    ) : (
                                                                                                        <>
                                                                                                          <span>{item.file_name}</span>
                                                                                                        </>
                                                                                                     )
                                                                                              }
                                                                                                <i className="rt-download">
                                                                                                    <AiOutlineDownload onClick={(e) => handleDownload(item.file_url)}/>
                                                                                                </i>
                                                                                            </div>
                                                                                        </>
                                                                                    ) : (
                                                                                        ""
                                                                                    )}
                                                                                    <span className="text-white">
                                                                                           {item.file_name}
                                                                                      </span>
                                                                                </div>
                                                                                <div className="rt-description">
                                                                                    <p>
                                                                                        {item?.message}
                                                                                    </p>
                                                                                </div>

                                                                            </div>
                                                                            <div className="figure ml-3"><img src="/assets/images/logo.png" alt="user image" className="!w-[25px] !h-[25px]"/></div>
                                                                        </div>
                                                                    </>
                                                                )}
                                                            </>
                                                        )
                                                    )
                                                    : <div className="flex justify-center items-center min-h-[650px]">
                                                        <img src="/assets/images/nodata-found.png" width="400px" alt="no data found_2"/>
                                                    </div>
                                                }
                                                {previewImage ? (
                                                    <>
                                                        <div className="rt-attechmentdetail absolute bottom-[70px] bg-white z-[99]">
                                                            <div className="rt-roundimage rt-uploadfile">
                                                                <img width="50px" src={previewImage} alt="Preview"/>
                                                                <button className="rt-close" onClick={(e) => removeAttachment(e)}><AiFillCloseCircle/></button>
                                                            </div>
                                                        </div>
                                                    </>
                                                ) : "" }
                                                <div ref={messagesEndRef}/>
                                            </div>
                                        </>
                                    ) : <div className="h-[calc(85%-29px)] flex justify-center items-center">
                                        <strong>
                                            Select chat to load messages
                                        </strong>
                                        </div> /*(
                                        <div className="flex justify-center items-center min-h-[650px]">
                                            <img src="/assets/images/nodata-found.png" width="320px" alt="no data found"/>
                                        </div>
                                    )*/
                                    }
                                    {bool ? (
                                        <>
                                            <div className="rt-chatfooter">
                                                <form className="at-themeform rt-textbox"
                                                    onSubmit={(e) => {e.preventDefault();}}>
                                                    <input
                                                        type="text"
                                                        className="form-control border-bordercolor ring-bordercolor focus:border-themecolor focus:ring-themecolor focus:shadow-none"
                                                        value={adminReply} onChange={(e) => setAdminReply(e.target.value)}
                                                        name="admin_reply" onKeyDown={handleKeyDown}
                                                        placeholder="Write your message"
                                                    />
                                                    <div className="rt-actionbtns">
                                                        <label
                                                            className="rt-uploadimage rt-roundicon rt-attachment-select-btn">
                                                            <input
                                                                type="file" name="image" className="form-control file-input"
                                                                onChange={(e) => handleFileUpload(e)}
                                                            />
                                                            <GrAttachment className="text-xl text-themecolor stroke-themecolor" />
                                                        </label>
                                                        <button type="button" className="rt-send">
                                                            <HiPaperAirplane className="text-2xl" onClick={debouncedAPICall} disabled={isFetching}/>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </>
                                    ) : (
                                        ""
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                    <NewChat
                        isOpen={isOpenOne}
                        closeModal={closeModalOne}
                        chatUsers={usersList}
                        onChangeUser={handleUserChange}
                    />
                </>
            )}
        </AuthenticatedLayout>
    );
}
