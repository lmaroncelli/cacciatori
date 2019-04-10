# pushed to github

luigi@LuigiMint /var/www/html/caccia $
git init 
git status 
git add --all .
git commit -m "first commit"
git status 
git remote add origin https://github.com/lmaroncelli/cacciatori.git
git push -u origin master


To grab a complete copy of another user's repository, use git clone like this:

$ git clone https://github.com/lmaroncelli/cacciatori.git
# Clones a repository to your computer

git pull remotename branchname
# Grabs online updates and merges them with your local work