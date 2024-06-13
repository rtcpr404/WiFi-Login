param(
    [string]$UserAccount,
    [string]$UserPassword,
    [string]$GivenName,
    [string]$Surname,
    [string]$EmailAddress
  
)

Import-Module ActiveDirectory

# Written By: http://vcloud-lab.com
# Date: 19 January 2022
# Env: Powershell 5.1, PHP (latest), JQuery (latest), HTML 5, CSS, XAMPP

#$password = ConvertFrom-SecureString -SecureString $UserPassword  -AsPlainText

#$passwordLenght = 8
#Add-Type -AssemblyName System.Web
#$password = [System.Web.Security.Membership]::GeneratePassword($passwordLenght,1)
#$encryptedPassword = ConvertTo-SecureString $password -AsPlainText -Force
$encryptedPassword = ConvertTo-SecureString $UserPassword -AsPlainText -Force

$userParams = @{
    GivenName = $GivenName
    Surname = $Surname
    Name = $GivenName
    SamAccountName = $UserAccount
    Path = "OU=wifi,DC=imm,DC=go,DC=th" # Adjust the OU path as necessary
    EmailAddress = $EmailAddress
    DisplayName = $GivenName
    AccountPassword = $encryptedPassword
    Enabled = $true
    PasswordNeverExpires = $true

}

try {
 #   $userInfo = Get-ADUser -Identity $UserAccount -Properties LockedOut -ErrorAction Stop
 #   $htmlReport = "<h3>User account '$UserAccount' found in AD</h3>`n"
# New-ADUser -Name $UserAccount -Surname $Surname -Path "OU=wifi,DC=imm,DC=go,DC=th" -AccountPassword $encryptedPassword -Enabled $True
# $htmlReport += "<h2>Create User: $UserAccount $Surname success</h2>`n"
   # Set-ADUser -Identity $UserAccount -ChangePasswordAtLogon $true -ErrorAction Stop
    
# Define the user properties

# Create AD user
New-ADUser @userParams

# Add user to group
Add-ADGroupMember -Identity "wifi" -Members $UserAccount

$htmlReport += "<h2>Create User: $UserAccount $Surname success</h2>`n"

    if (!$userInfo.LockedOut)
    {
        Unlock-ADAccount -Identity $UserAccount -ErrorAction Stop
       # $htmlReport += "<h3>User account is unlocked</h3>`n"
    }
    $htmlReport 
    $htmlReport | Out-File c:\temp\logs.txt -Append
}
catch {
    "<h3>User $UserAccount Password Reset failed`n</h3>"
    $htmlReport | Out-File c:\temp\logs.txt -Append
}