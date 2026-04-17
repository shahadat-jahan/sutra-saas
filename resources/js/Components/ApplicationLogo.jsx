export default function ApplicationLogo(props) {
    return (
        <svg
            {...props}
            viewBox="0 0 40 40"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M20 5L33 12.5V27.5L20 35L7 27.5V12.5L20 5Z"
                className="fill-indigo-600"
                fillOpacity="0.1"
            />
            <path
                d="M20 10L28.6603 15V25L20 30L11.3397 25V15L20 10Z"
                className="stroke-indigo-600"
                strokeWidth="2"
            />
            <path
                d="M15 18C15 15.2386 17.2386 13 20 13C22.7614 13 25 15.2386 25 18C25 20.7614 22.7614 23 20 23"
                className="stroke-indigo-600"
                strokeWidth="2.5"
                strokeLinecap="round"
            />
            <path
                d="M15 22C15 24.7614 17.2386 27 20 27C22.7614 27 25 24.7614 25 22C25 19.2386 22.7614 17 20 17"
                className="stroke-indigo-600"
                strokeWidth="2.5"
                strokeLinecap="round"
            />
        </svg>
    );
}
