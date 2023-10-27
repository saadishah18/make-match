import { useState, CSSProperties } from "react";
import ClipLoader from "react-spinners/ClipLoader";


function LoadingCirlce(props) {
    // debugger;
    let [loading, setLoading] = useState(props.loading);
    let [color, setColor] = useState("#C43087");

    return (
        <div className="sweet-loading w-full h-screen flex justify-center items-center">
            {/*<button onClick={() => setLoading(!loading)}>Toggle Loader</button>*/}
            {/*<input value={color} onChange={(input) => setColor(input.target.value)} placeholder="Color of the loader" />*/}

            <ClipLoader
                color={color}
                loading={loading}
                // cssOverride={override}
                size={200}
                aria-label="Loading Spinner"
                data-testid="loader"
            />
        </div>
    );
}

export default LoadingCirlce;
