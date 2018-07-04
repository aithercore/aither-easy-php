# API Document:
## help
#### Addressindex 
- getaddressbalance 
- getaddressdeltas 
- getaddressmempool 
- getaddresstxids 
- getaddressutxos 
  
#### Aither 
- getgovernanceinfo 
- getpoolinfo 
- getsuperblockbudget index 
- gobject "command"... 
- masternode "command"... 
- masternodebroadcast "command"... 
- masternodelist ( "mode" "filter" ) 
- mnsync [status|next|reset] 
- privatesend "command" 
- sentinelping version 
- spork <name> [<value>] 
- voteraw <masternode-tx-hash> <masternode-tx-index> <governance-hash> <vote-signal> [yes|no|abstain] <time> <vote-sig> 
  
#### Blockchain 
- getbestblockhash 
- getblock "hash" ( verbose ) 
- getblockchaininfo 
- getblockcount 
- getblockhash index 
- getblockhashes timestamp 
- getblockheader "hash" ( verbose ) 
- getblockheaders "hash" ( count verbose ) 
- getchaintips ( count branchlen ) 
- getdifficulty 
- getmempoolinfo 
- getrawmempool ( verbose ) 
- getspentinfo 
- gettxout "txid" n ( includemempool ) 
- gettxoutproof ["txid",...] ( blockhash ) 
- gettxoutsetinfo 
- verifychain ( checklevel numblocks ) 
- verifytxoutproof "proof" 
  
#### Control 
- debug ( 0|1|addrman|alert|bench|coindb|db|lock|rand|rpc|selectcoins|mempool|mempoolrej|net|proxy|prune|http|libevent|tor|zmq|aither|privatesend|instantsend|masternode|spork|keepass|mnpayments|gobject ) 
- getinfo 
- help ( "command" ) 
- stop 
  
#### Generating 
- generate numblocks 
- getgenerate 
- setgenerate generate ( genproclimit ) 
  
#### Mining 
- getblocktemplate ( "jsonrequestobject" ) 
- getmininginfo 
- getnetworkhashps ( blocks height ) 
- prioritisetransaction <txid> <priority delta> <fee delta> 
- submitblock "hexdata" ( "jsonparametersobject" ) 
  
#### Network 
- addnode "node" "add|remove|onetry" 
- clearbanned 
- disconnectnode "node" 
- getaddednodeinfo dummy ( "node" ) 
- getconnectioncount 
- getnettotals 
- getnetworkinfo 
- getpeerinfo 
- listbanned 
- ping 
- setban "ip(/netmask)" "add|remove" (bantime) (absolute) 
- setnetworkactive true|false 
  
#### Rawtransactions 
- createrawtransaction [{"txid":"id","vout":n},...] {"address":amount,"data":"hex",...} ( locktime ) 
- decoderawtransaction "hexstring" 
- decodescript "hex" 
- fundrawtransaction "hexstring" includeWatching 
- getrawtransaction "txid" ( verbose ) 
- sendrawtransaction "hexstring" ( allowhighfees instantsend ) 
- signrawtransaction "hexstring" ( [{"txid":"id","vout":n,"scriptPubKey":"hex","redeemScript":"hex"},...] ["privatekey1",...] sighashtype ) 
  
#### Util 
- createmultisig nrequired ["key",...] 
- estimatefee nblocks 
- estimatepriority nblocks 
- estimatesmartfee nblocks 
- estimatesmartpriority nblocks 
- validateaddress "aitheraddress" 
- verifymessage "aitheraddress" "signature" "message" 
  
#### Wallet 
- abandontransaction "txid" 
- addmultisigaddress nrequired ["key",...] ( "account" ) 
- backupwallet "destination" 
- dumphdinfo 
- dumpprivkey "aitheraddress" 
- dumpwallet "filename" 
- encryptwallet "passphrase" 
- getaccount "aitheraddress" 
- getaccountaddress "account" 
- getaddressesbyaccount "account" 
- getbalance ( "account" minconf addlockconf includeWatchonly ) 
- getnewaddress ( "account" ) 
- getrawchangeaddress 
- getreceivedbyaccount "account" ( minconf addlockconf ) 
- getreceivedbyaddress "aitheraddress" ( minconf addlockconf ) 
- gettransaction "txid" ( includeWatchonly ) 
- getunconfirmedbalance 
- getwalletinfo 
- importaddress "address" ( "label" rescan p2sh ) 
- importelectrumwallet "filename" index 
- importprivkey "aitherprivkey" ( "label" rescan ) 
- importpubkey "pubkey" ( "label" rescan ) 
- importwallet "filename" 
- instantsendtoaddress "aitheraddress" amount ( "comment" "comment-to" subtractfeefromamount ) 
- keepass <genkey|init|setpassphrase> 
- keypoolrefill ( newsize ) 
- listaccounts ( minconf addlockconf includeWatchonly) 
- listaddressgroupings 
- listlockunspent 
- listreceivedbyaccount ( minconf addlockconf includeempty includeWatchonly) 
- listreceivedbyaddress ( minconf addlockconf includeempty includeWatchonly) 
- listsinceblock ( "blockhash" target-confirmations includeWatchonly) 
- listtransactions    ( "account" count from includeWatchonly) 
- listunspent ( minconf maxconf ["address",...] ) 
- lockunspent unlock [{"txid":"txid","vout":n},...] 
- move "fromaccount" "toaccount" amount ( minconf "comment" ) 
- sendfrom "fromaccount" "toaitheraddress" amount ( minconf addlockconf "comment" "comment-to" ) 
- sendmany "fromaccount" {"address":amount,...} ( minconf addlockconf "comment" ["address",...] subtractfeefromamount use_is use_ps ) 
- sendtoaddress "aitheraddress" amount ( "comment" "comment-to" subtractfeefromamount use_is use_ps ) 
- setaccount "aitheraddress" "account" 
- settxfee amount 
- signmessage "aitheraddress" "message"

## getinfo
Returns an object containing various state info.

Result:
```
{
  "version": xxxxx,           (numeric) the server version
  "protocolversion": xxxxx,   (numeric) the protocol version
  "walletversion": xxxxx,     (numeric) the wallet version
  "balance": xxxxxxx,         (numeric) the total aither balance of the wallet
  "privatesend_balance": xxxxxx, (numeric) the anonymized aither balance of the wallet
  "blocks": xxxxxx,           (numeric) the current number of blocks processed in the server
  "timeoffset": xxxxx,        (numeric) the time offset
  "connections": xxxxx,       (numeric) the number of connections
  "proxy": "host:port",     (string, optional) the proxy used by the server
  "difficulty": xxxxxx,       (numeric) the current difficulty
  "testnet": true|false,      (boolean) if the server is using testnet or not
  "keypoololdest": xxxxxx,    (numeric) the timestamp (seconds since GMT epoch) of the oldest pre-generated key in the key pool
  "keypoolsize": xxxx,        (numeric) how many new keys are pre-generated
  "unlocked_until": ttt,      (numeric) the timestamp in seconds since epoch (midnight Jan 1 1970 GMT) that the wallet is unlocked for transfers, or 0 if the wallet is locked
  "paytxfee": x.xxxx,         (numeric) the transaction fee set in AIT/kB
  "relayfee": x.xxxx,         (numeric) minimum relay fee for non-free transactions in AIT/kB
  "errors": "..."           (string) any error messages
}
```
Examples:
```
> aither-cli getinfo
> curl --user myusername --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getinfo", "params": [] }' -H 'content-type: text/plain;' http://127.0.0.1:40999/
```
