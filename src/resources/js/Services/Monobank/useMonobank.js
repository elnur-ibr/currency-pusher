import {useMemo} from 'react';
import {useQuery} from 'react-query';

const useMonobank = () => {
  useMemo(
    () => ({
      useCurrency: () => useQuery('monobank', () => monobankApi.currency())
    })
  )
}
