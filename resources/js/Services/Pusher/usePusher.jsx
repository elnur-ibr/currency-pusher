import { Link, Head } from '@inertiajs/react';
import Pusher from 'pusher-js';
import {useEffect} from "react";

const pusher = new Pusher('1677401d496e6d9fdceb', {
    cluster: 'eu',
});

const channel = pusher.subscribe('monobank');

channel.bind('currency-updated', function () {
  console.log('Received event')
  alert('Received event')
});

const usePusher = () => {

}

export default usePusher()

