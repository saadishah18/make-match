import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import Slider from "react-slick";

const SeamlessProcess = () => {
    const settings = {
        dots: true,
        arrows: false,
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true,
                },
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    initialSlide: 2,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
        ],
    };

    return (
        <div
            id="screenshots"
            className="relative rt-gradientbg py-20 sm:py-[100px] 2xl:pt-[140px] xl:pb-[120px]"
        >
            <div className="container">
                <h2 className="text-center text-[2.7rem] md:text-[3.3rem] xl:text-[4rem] 2xl:text-[4.3rem] font-gilroy-bold mb-8 text-white">
                    Seamless Process
                </h2>
                <p className="text-center max-w-[1146px] mx-auto font-gilroy-medium text-xl mb-[40px] md:mb-[70px] xl:mb-[100px] text-white text-opacity-70">
                    Discover the simplicity of MyNikahNow App! Our user-friendly
                    interface and seamless, fully guided step-by-step process
                    make it extra easy to use. From sign up to obtaining your
                    Nikah certificate, our app ensures a smooth experience.
                    Plus, if you need assistance, the helpdesk is just a few
                    taps away with our convenient In-App-Chat feature.
                </p>
                <Slider {...settings}>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img1.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img2.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img3.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img4.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img5.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img6.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img7.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img8.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img9.png"
                            alt=""
                        />
                    </div>
                    <div>
                        <img
                            src="/assets/images/landingpage-img/app-img10.png"
                            alt=""
                        />
                    </div>
                </Slider>
            </div>
        </div>
    );
};

export default SeamlessProcess;
