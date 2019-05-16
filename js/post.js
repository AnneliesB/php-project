

const post = (postId) => {
    console.log(postId);
    const formInput = document.getElementById(postId).children.comment.value;
    postComment(formInput, postId);
    document.getElementById(postId).children.comment.value = "";
}

const postComment = async (comment, postId) => {
    if(comment.length > 0){

    
    data = {
        postid: postId,
        comment, comment
    };

    const toGetParams = `postid=${data.postid}&comment=${data.comment}&date=${data.date}`;
    try{
        const { data } = await axios.post("ajax/postcomment.php?" + toGetParams)
    } catch (e){
        console.log(e);
    }
}
    
}

function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
  }

const changePosts = async (postId) => {
    const box = document.getElementById("commentContainer"); //select container box
    sleep(50); //sleeps for 50 ms else crash

    const { data } = await axios.get("ajax/getComments.php?id=" + postId); //get comment info from current post
    if(data.length > 0)
        document.querySelector("p.postContainer").remove();   
    if(data.length > box.children.length){ //checks if there is new data
        //console.log(data);
        const children = box.children.length; //get offset, voegt nieuwste comment toe
        let countFrom = 0; //default state for comment.
        if(box.children.length > 0) countFrom = children; //checks if there are comments

        for(let i = countFrom; i < data.length; i++){ //loopt over nieuwe data 
            const main = document.createElement("DIV");
            main.className = "comments";
            const text = document.createElement("P");
            text.innerHTML = `${data[i].username}: ${data[i].comment}`; //plaatst comment in div
            main.appendChild(text);
            box.appendChild(main); //comment container
        }
    }
    changePosts(postId);
}
var test = document.querySelectorAll(".commentBtn");
test.forEach((btn)=>{
    const id = btn.dataset.id;
    btn.onclick = ()=>post(id);
})