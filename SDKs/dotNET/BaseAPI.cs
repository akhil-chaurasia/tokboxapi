/*
 * Copyright (c) 2008 Syedur Islam
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *		
 *************************************

 *
 *       TokBox Base API             *
 *
 *************************************
 *
 * Original Java Code by Melih Onvural, August 2008

 * Converted to C# by Syedur Islam, November 2008

 *

 *

 */

using System;
using System.Collections;
using System.Collections.Generic;
using System.Net;
using System.Text;
using System.IO;
using System.Security.Cryptography;
using System.Web;

namespace TokBox
{
    public class BaseAPI
    {
        private string partnerKey;
        private string secret;

        private string jabberId;

        private const string version = "1.0.0";
        private const int sigMethod = SIMPLE_MD5;

        public const int SIMPLE_MD5 = 1;
        public const int HMAC_SHA1 = 2;

        public const string API_SERVER_LOGIN_URL = "view/oauth&";
        public const string API_SERVER_METHODS_URL = "a/v0";
        public const string API_SERVER_CALL_WIDGET = "vc/";
        public const string API_SERVER_RECORDER_WIDGET = "vr/";
        public const string API_SERVER_PLAYER_WIDGET = "vp/";

        public BaseAPI(string partnerKey, string secret)
        {
            this.partnerKey = partnerKey;
            this.secret = secret;
            this.jabberId = "";
        }

        public string GetPartnerKey()
        {
            return this.partnerKey;
        }

        public string GetSecret()
        {
            return this.secret;
        }

        public string GetJabberId()
        {
            return this.jabberId;
        }

        public void SetJabberId(string jabberId)
        {
            this.jabberId = jabberId;
        }

        public void SetSecret(string secret)
        {
            this.secret = secret;
        }

        public string SigMethodText()
        {
            return sigMethod == SIMPLE_MD5 ? "SIMPLE-MD5" : "HMAC-SHA1";
        }

        protected string TB_Request(string method, string apiUrl, Dictionary<string, string> paramList)
        {
            string reqString = API_Config.API_SERVER + API_SERVER_METHODS_URL + apiUrl;

            WebRequest request = WebRequest.Create(reqString);
            try
            {
                string nonce = this.GenerateNonce();
                
				//string timestamp = DateTime.Now.Millisecond.ToString();
				//generate a unix timestamp -- modified by Volkan Özçelik @2009-01-09,21:36,GMT+2
				TimeSpan span = DateTime.Now - (new DateTime(1970, 1, 1, 0, 0, 0, 0));
				string timestamp = "" + Convert.ToInt64(span.TotalSeconds);

				string signedSig = this.BuildSignedRequest(method, reqString, nonce, timestamp, paramList);

				StringBuilder dataString = new StringBuilder();

                foreach (KeyValuePair<string, string> kvp in paramList)
                {
                    dataString.Append(HttpUtility.UrlEncode(kvp.Key, Encoding.UTF8));
                    dataString.Append("=");
                    dataString.Append(HttpUtility.UrlEncode(kvp.Value, Encoding.UTF8).Replace("+", "%20"));
                    dataString.Append("&");
                }


                //Add the _AUTHORIZATION to parameter string
                dataString.Append("_AUTHORIZATION=");

                //Adding the oauth_partner_key to the _AUTHORIZATION 
                dataString.Append("oauth_partner_key=\"").Append(HttpUtility.UrlEncode(partnerKey, Encoding.UTF8)).Append("\",");

                //Adding the oauth_signature_method to the _AUTHORIZATION
                dataString.Append("oauth_signature_method=\"").Append(HttpUtility.UrlEncode(SigMethodText(), Encoding.UTF8)).Append("\",");

                //Adding the oauth_timestamp to the _AUTHORIZATION
                dataString.Append("oauth_timestamp=\"").Append(HttpUtility.UrlEncode(timestamp, Encoding.UTF8)).Append("\",");

                //Adding oauth_version to the _AUTHORIZATION
                dataString.Append("oauth_version=\"").Append(HttpUtility.UrlEncode(version, Encoding.UTF8)).Append("\",");

                //Adding oauth_nonce to the _AUTHORIZATION
                dataString.Append("oauth_nonce=\"").Append(HttpUtility.UrlEncode(nonce, Encoding.UTF8)).Append("\",");

                //Adding oauth_signature to the _AUTHORIZATION
                dataString.Append("oauth_signature=\"").Append(HttpUtility.UrlEncode(signedSig, Encoding.UTF8)).Append("\",");

                //Adding tokbox_jabberid to the _AUTHORIZATION
                dataString.Append("tokbox_jabberid=\"").Append(HttpUtility.UrlEncode(this.jabberId, Encoding.UTF8)).Append("\"");

                byte[] bytes = Encoding.UTF8.GetBytes(dataString.ToString());

                request.ContentType = "application/x-www-form-urlencoded";
                request.ContentLength = bytes.Length;
                request.Method = method;

                using (Stream requestStream = request.GetRequestStream())
                {
                    requestStream.Write(bytes, 0, bytes.Length);

                    try
                    {
                        using (WebResponse response = request.GetResponse())
                        {
                            using (StreamReader reader = new StreamReader(response.GetResponseStream()))
                            {
                                return reader.ReadToEnd();
                            }
                        }
                    }
                    catch (Exception ex)
                    {
                        throw new Exception(ex.StackTrace);
                    }
                }
            }
            catch (Exception ex)
            {
                throw new Exception(ex.StackTrace);
            }

        }

        private string BuildSignedRequest(string method, string uri, string nonce, string timestamp, Dictionary<string, string> paramList)
        {
            string signedString = null;

            paramList.Add("oauth_partner_key", this.partnerKey);
            paramList.Add("oauth_signature_method", this.SigMethodText());
            paramList.Add("oauth_timestamp", timestamp);
            paramList.Add("oauth_version", version);
            paramList.Add("oauth_nonce", nonce);
            paramList.Add("tokbox_jabberid", this.jabberId);

            StringBuilder requestString = new StringBuilder();
            requestString.Append(method).Append("&").Append(uri).Append("&").Append(GenerateRequestString(paramList)).Append(secret);

            Console.WriteLine("Request string is: " + requestString);

            switch (sigMethod)
            {
                case SIMPLE_MD5:
                    signedString = MD5Hash(requestString.ToString());
                    break;
                case HMAC_SHA1:
                    //not currently implemented
                    break;
                default:
                    throw new TokBoxRuntimeException("Desired Signature Method is not supported", null);
            }

            Console.WriteLine("Signed string is: " + signedString);

            return signedString;
        }

        private string GenerateRequestString(Dictionary<string, string> paramList)
        {
            List<string> encodedParamList = new List<string>();

            foreach (KeyValuePair<string, string> kvp in paramList)
            {
                if (!string.IsNullOrEmpty(kvp.Key) && !string.IsNullOrEmpty(kvp.Value))
                {
                    StringBuilder encodedString = new StringBuilder();

                    string encodedKey = HttpUtility.UrlEncode(kvp.Key, Encoding.UTF8);
                    string encodedValue = HttpUtility.UrlEncode(kvp.Value, Encoding.UTF8)
                        .Replace("+", "%20")
                        .Replace("%3a", "%3A")
                        .Replace("%2f","%2F");
                    encodedString.AppendFormat("{0}={1}", encodedKey, encodedValue);

                    encodedParamList.Add(encodedString.ToString());
                }
            }

            encodedParamList.Sort();

            Console.WriteLine("EncodedParamList: " + TokBoxUtils.Join(encodedParamList, "&"));
            return TokBoxUtils.Join(encodedParamList, "&");
        }

        private string GenerateNonce()
        {
            StringBuilder nonce = new StringBuilder(16);
            const int length = 16;
            Random generator = new Random();

            const string seed = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            int seedLength = seed.Length - 1;

            for (int i = 0; i < length; i++)
            {
                nonce.Append(seed[generator.Next(seedLength)]);
            }

            Console.WriteLine("Nonce: " + nonce);
            return nonce.ToString();
        }

        private string MD5Hash(string token)
        {
            MD5 md5 = MD5.Create();
            byte[] data = md5.ComputeHash(Encoding.Default.GetBytes(token));
            StringBuilder sb = new StringBuilder();

            for (int i = 0; i < data.Length; i++)
            {
                sb.Append(data[i].ToString("x2"));
            }

            Console.WriteLine("MD5 Hash: " + sb);
            return sb.ToString();
        }
    }
}