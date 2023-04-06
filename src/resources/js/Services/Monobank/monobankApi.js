import axios from 'axios';
import {MONOBANK_API_URL, MONOBANK_KEY} from "@/Services/Monobank/monobankConstant";

axios.defaults.baseURL = MONOBANK_API_URL;

const monobankApi = {
  async currency() {
    const {data} = await axios.get(MONOBANK_KEY.currency)

    return data
  },
}
