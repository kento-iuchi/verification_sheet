function getArrayDiff(arr1, arr2) {
  let arr = arr1.concat(arr2);
  return arr.filter((v, i)=> {
    return !(arr1.indexOf(v) !== -1 && arr2.indexOf(v) !== -1);
  });
}
