export const getCookieValue = (searchKey: string) => {
  if (!searchKey) {
    return '';
  }
  let val = '';
  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=')
    if (key === searchKey) {
      return val = value;
    }
  })
  return val;
}
