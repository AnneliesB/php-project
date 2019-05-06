const post = (postId) => {
    console.log(postId);
    const formInput = document.getElementById(postId).children.comment.value;
    postComment(formInput, postId);
    document.getElementById(postId).children.comment.value = "";
}

const postComment = async (comment, postId) => {
    data = {
        postid: postId,
        comment, comment,
        date: '00-00-00 00:00:00'
    };

    const toGetParams = `postid=${data.postid}&comment=${data.comment}&date=${data.date}`;
    try{
        const { data } = await axios.post("ajax/postcomment.php?" + toGetParams)
    } catch (e){
        console.log(e);
    }
    
}

function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
  }

// This function is a little broken
// Works only in Details
//WORK ON FIX!! 
const changePosts = async (postId) => {
    debugger;
    const box = document.getElementById("commentContainer");
    sleep(50);

    const { data } = await axios.get("ajax/getComments.php?id=" + postId);
    if(data.length > box.children.length){
        console.log(data);
        const children = box.children.length;
        let countFrom = 0;
        if(box.children.length > 0) countFrom = children;
        for(let i = countFrom; i < data.length; i++){
            const main = document.createElement("DIV");
            main.className = "comments";
            const text = document.createElement("P");
            text.innerHTML = `${data[i].date}: ${data[i].username}: ${data[i].comment}`;
            main.appendChild(text);
            box.appendChild(main);
        }
    }


    changePosts(postId);
}