async function getDipChartData(id) {
      const response = await axios.get(`/api/tanks/${id}/dip-chart`);
      return response.data;
  }
  export { getDipChartData };