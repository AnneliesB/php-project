

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

        const toGetParams = `postid=${data.postid}&comment=${data.comment}`; //query van comment aanmaken
        try{
            await axios.post("ajax/postcomment.php?" + toGetParams) // stuurt query naar server 
        } catch (e){
            console.log(e);
        }
    }
    
}


const changePosts = async (postId) => {
    const box = document.getElementById("commentContainer"); //select container box
    
    const { data } = await axios.get("ajax/getComments.php?id=" + postId); //get comment info from current post

    if(data.length > 0){ //comments > 0
        document.querySelector("p.postContainer").remove();  //remover "be first to comment"
    } 

    if(data.length > box.children.length){ //checks if there is new data (how many consts are there, how many do i need to add)
        
        const children = box.children.length; //get offset, voegt nieuwste comment toe
        let countFrom = 0; //to get correct offset 
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
//geef comment button event om een comment te kunnen posten => onclick
var test = document.querySelectorAll(".commentBtn");
test.forEach((btn)=>{
    const id = btn.dataset.id;
    btn.onclick = ()=>post(id);
})