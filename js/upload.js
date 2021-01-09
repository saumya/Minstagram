console.log('Upload : Version 1.1.0');

(()=>{
  console.log('Application');
  // hide the progress bars
  const hideProgressbars = function(){
    const p1 = document.getElementById('progress_1');
    const p2 = document.getElementById('progress_2');
    const p3 = document.getElementById('progress_3');

    p1.style.display = 'none';
    p2.style.display = 'none';
    p3.style.display = 'none';
  };
  const showProgressBarWithId = function(id){
    const el = document.getElementById(id);
    el.style.display = '';
  };
  const hideProgressBarWithId = function(id){
    const el = document.getElementById(id);
    el.style.display = 'none';
  };
  //
  hideProgressbars();
  // Define processing URL and form element
  const url = 'upload_to_minstagram.php';
  const form = document.querySelector('form');
  // Listen for form submit
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    //
    showProgressBarWithId('progress_1');
    //
    const files = document.querySelector('[type=file]').files;
    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
      let file = files[i];
      formData.append('files[]', file);
    }
    //
    fetch ( url, {
      method: 'POST',
      body: formData
    }).then( response => {
      //console.log('response');
      //console.log(response);
      // JSON.stringify(
      //document.getElementById('id_result').innerHTML = 'Upload Success.';
      //console.log ( JSON.stringify( response ) );
      
      response.text().then( result => {
        //console.log('result');
        //debugger;

        hideProgressbars();
        
        console.log('result', result);
        const a1 = result.substr(1,result.length);
        const a2 = a1.substr(0,result.length-2);
        const a3 = a2.split(',');
        const numFiles = a3.length;

        const isResultTrue = a3.every(function(item){ return( item==='true' ) });

        let sResult = 'Upload Error! Some files did not get uploaded.';
        if(isResultTrue){
          sResult = `Upload Success. Total ${a3.length} files uploaded. `;
        }
        document.getElementById('id_result').innerHTML = sResult;

        
      }).catch(error2=>console.log(error2));
      
      //
      /*
      response.json().then( result=>{
        console.log('Result :', result);
      }).catch( error2=>
        console.log('Error 2 :',error2)
      );
      */
      //
    }).catch( error1 => console.log(error1) );
    //
    //console.log('Submit : action button', new Date());
  });
  //
})();// Self-Executing Function




